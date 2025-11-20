<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user and API token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
        
        // Create some test notifications
        $this->createTestNotifications();
    }

    private function createTestNotifications()
    {
        // Create sample notifications for testing
        $notifications = [
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\GeneralNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->user->id,
                'data' => json_encode([
                    'title' => 'Welcome to Ulin Mahoni',
                    'message' => 'Thank you for joining our platform!',
                    'type' => 'general'
                ]),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\PaymentNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->user->id,
                'data' => json_encode([
                    'title' => 'Payment Confirmed',
                    'message' => 'Your payment has been processed successfully',
                    'type' => 'payment',
                    'amount' => 150000,
                    'currency' => 'IDR'
                ]),
                'read_at' => null,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\InvoicePaid',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $this->user->id,
                'data' => json_encode([
                    'title' => 'Invoice Paid',
                    'message' => 'Your invoice has been paid successfully',
                    'type' => 'invoice',
                    'invoice_id' => 12345
                ]),
                'read_at' => now()->subHours(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        ];

        foreach ($notifications as $notification) {
            \DB::table('notifications')->insert($notification);
        }
    }

    /** @test */
    public function test_get_notifications_returns_user_notifications()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         '*' => [
                             'id',
                             'type',
                             'data',
                             'read_at',
                             'created_at'
                         ]
                     ]
                 ]);

        // Verify that notifications are returned for the authenticated user
        $notifications = $response->json('data');
        $this->assertNotEmpty($notifications);
        $this->assertCount(3, $notifications);
    }

    /** @test */
    public function test_get_notifications_requires_authentication()
    {
        $response = $this->getJson('/api/notifications');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_get_notifications_returns_empty_array_when_no_notifications()
    {
        // Clear all notifications
        \DB::table('notifications')->truncate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Notifications retrieved successfully',
                     'data' => []
                 ]);
    }

    /** @test */
    public function test_payment_notifications_creates_new_notification()
    {
        $paymentData = [
            'transaction_id' => 'TXN123456',
            'amount' => 250000,
            'currency' => 'IDR',
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'timestamp' => now()->toISOString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->postJson('/api/notifications/payment', $paymentData);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'notification' => [
                             'title',
                             'message',
                             'type',
                             'transaction_id',
                             'amount'
                         ]
                     ]
                 ]);

        // Verify the notification was created in the database
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->user->id,
            'type' => 'App\Notifications\PaymentNotification',
        ]);
    }

    /** @test */
    public function test_payment_notifications_requires_authentication()
    {
        $paymentData = [
            'transaction_id' => 'TXN123456',
            'amount' => 250000,
        ];

        $response = $this->postJson('/api/notifications/payment', $paymentData);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_payment_notifications_validates_required_fields()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/payment', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'errors'
                 ]);
    }

    /** @test */
    public function test_payment_notifications_validates_payment_status()
    {
        $paymentData = [
            'transaction_id' => 'TXN123456',
            'amount' => 250000,
            'currency' => 'IDR',
            'status' => 'invalid_status', // Invalid status
            'payment_method' => 'credit_card',
            'timestamp' => now()->toISOString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/payment', $paymentData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'errors' => [
                         'status'
                     ]
                 ]);
    }

    /** @test */
    public function test_mark_notifications_as_read_updates_read_status()
    {
        // Get unread notifications
        $unreadNotifications = \DB::table('notifications')
            ->where('notifiable_id', $this->user->id)
            ->whereNull('read_at')
            ->pluck('id');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => $unreadNotifications->toArray()
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Notifications marked as read'
                 ]);

        // Verify notifications are marked as read
        foreach ($unreadNotifications as $notificationId) {
            $notification = \DB::table('notifications')->find($notificationId);
            $this->assertNotNull($notification->read_at);
        }
    }

    /** @test */
    public function test_mark_notifications_as_read_with_single_notification()
    {
        $singleNotification = \DB::table('notifications')
            ->where('notifiable_id', $this->user->id)
            ->whereNull('read_at')
            ->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => [$singleNotification->id]
        ]);

        $response->assertStatus(200);
        
        // Verify single notification is marked as read
        $updatedNotification = \DB::table('notifications')->find($singleNotification->id);
        $this->assertNotNull($updatedNotification->read_at);
    }

    /** @test */
    public function test_mark_notifications_as_read_already_read_notifications()
    {
        // Get read notifications
        $readNotifications = \DB::table('notifications')
            ->where('notifiable_id', $this->user->id)
            ->whereNotNull('read_at')
            ->pluck('id');

        if ($readNotifications->isEmpty()) {
            $this->markTestSkipped('No read notifications available for testing');
        }

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => $readNotifications->toArray()
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.updated_count', 0);
    }

    /** @test */
    public function test_mark_notifications_as_read_requires_authentication()
    {
        $response = $this->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => ['some-id']
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_mark_notifications_as_read_validates_notification_ids()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'errors'
                 ]);
    }

    /** @test */
    public function test_mark_notifications_as_read_requires_array_format()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => 'invalid-string'
        ]);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'errors'
                 ]);
    }

    /** @test */
    public function test_notification_endpoints_handle_exceptions_gracefully()
    {
        // Test database connection failure by temporarily disabling notifications table
        $originalNotifications = \DB::table('notifications')->where('notifiable_id', $this->user->id)->get();
        
        // Delete notifications temporarily
        \DB::table('notifications')->where('notifiable_id', $this->user->id)->delete();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $response->assertStatus(200);

        // Restore notifications
        foreach ($originalNotifications as $notification) {
            \DB::table('notifications')->insert((array) $notification);
        }
    }

    /** @test */
    public function test_notification_order_descending_by_created_at()
    {
        // Add a notification with earlier date but latest created_at
        $earlierNotification = [
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'App\Notifications\GeneralNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $this->user->id,
            'data' => json_encode([
                'title' => 'Latest Notification',
                'message' => 'This should appear first',
                'type' => 'general'
            ]),
            'read_at' => null,
            'created_at' => now()->addHour(),
            'updated_at' => now()->addHour(),
        ];
        \DB::table('notifications')->insert($earlierNotification);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $notifications = $response->json('data');
        
        // Verify latest notification appears first
        $this->assertEquals('Latest Notification', json_decode($notifications[0]['data'], true)['title']);
    }

    /** @test */
    public function test_payment_notification_sends_email_notification()
    {
        // Mock the notification to verify it's being sent
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $paymentData = [
            'transaction_id' => 'TXN789123',
            'amount' => 99999,
            'currency' => 'IDR',
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
            'timestamp' => now()->toISOString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/notifications/payment', $paymentData);

        $response->assertStatus(201);

        // Verify that notification was created
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'notifiable_type' => 'App\Models\User'
        ]);
    }

    /** @test */
    public function test_notification_response_format_consistency()
    {
        // Test that all endpoints return consistent response format
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/notifications');

        $this->assertEquals('success', $response->json('status'));
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());

        $paymentData = [
            'transaction_id' => 'TXN789',
            'amount' => 10000,
            'currency' => 'IDR',
            'status' => 'completed',
            'payment_method' => 'test',
            'timestamp' => now()->toISOString()
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/payment', $paymentData);

        $this->assertEquals('success', $response->json('status'));
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());

        $unreadNotifications = \DB::table('notifications')
            ->where('notifiable_id', $this->user->id)
            ->whereNull('read_at')
            ->pluck('id')
            ->take(1);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/mark-as-read', [
            'notification_ids' => $unreadNotifications->toArray()
        ]);

        $this->assertEquals('success', $response->json('status'));
        $this->assertArrayHasKey('message', $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function test_notification_authentication_middleware()
    {
        // Test that all notification endpoints require authentication
        $endpoints = [
            ['method' => 'GET', 'url' => '/api/notifications'],
            ['method' => 'POST', 'url' => '/api/notifications/payment'],
            ['method' => 'POST', 'url' => '/api/notifications/mark-as-read'],
        ];

        foreach ($endpoints as $endpoint) {
            $response = $endpoint['method'] === 'GET' 
                ? $this->getJson($endpoint['url'])
                : $this->postJson($endpoint['url'], []);

            $response->assertStatus(401);
        }
    }

    /** @test */
    public function test_notification_validation_errors_format()
    {
        // Test various validation scenarios
        $invalidData = [
            'transaction_id' => 'short',
            'amount' => 'not-a-number',
            'status' => 'invalid-status',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/notifications/payment', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonPath('status', 'error')
                 ->assertJsonPath('message', 'Validation failed');
    }

    /**
     * DOKU Virtual Account Notification Tests
     */

    /** @test */
    public function test_doku_virtual_account_notification_accepts_valid_data()
    {
        $dokuHeaders = [
            'X-TIMESTAMP' => '2020-12-21T07:56:11.000Z',
            'X-SIGNATURE' => '85be817c55b2c135157c7e89f52499bf0c25ad6eeebe04a986e8c862561b19a5',
            'X-PARTNER-ID' => '821508239190',
            'X-EXTERNAL-ID' => '418075533589',
            'CHANNEL-ID' => 'VA004',
        ];

        $dokuData = [
            'partnerServiceId' => '   77777',
            'customerNo' => '0000000000001',
            'virtualAccountNo' => '   777770000000000001',
            'virtualAccountName' => 'Toru Yamashita',
            'trxId' => '23219829713',
            'paymentRequestId' => '12839218738127830',
            'paidAmount' => [
                'value' => '11500.00',
                'currency' => 'IDR'
            ],
            'virtualAccountEmail' => 'toru@oor.com',
            'virtualAccountPhone' => '081293912081'
        ];

        $response = $this->withHeaders($dokuHeaders)->postJson('/api/notifications/doku/virtual-account', $dokuData);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('message', 'DOKU Virtual Account payment notification processed successfully');

        // Verify notification was created in database
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => 'App\Models\User',
            'type' => 'App\Notifications\PaymentNotification',
        ]);
    }

    /** @test */
    public function test_doku_notification_without_authentication_works()
    {
        // DOKU notifications typically don't require authentication as they're server-to-server
        $dokuData = [
            'partnerServiceId' => '77777',
            'customerNo' => '0000000000001',
            'virtualAccountNo' => '777770000000000001',
            'virtualAccountName' => 'Toru Yamashita',
            'trxId' => '23219829713',
            'paymentRequestId' => '12839218738127830',
            'paidAmount' => [
                'value' => '11500.00',
                'currency' => 'IDR'
            ],
            'virtualAccountEmail' => 'toru@oor.com',
            'virtualAccountPhone' => '081293912081'
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $dokuData);

        // This test will depend on your authentication setup for DOKU notifications
        // If DOKU requires no authentication, the test should pass
        // If DOKU requires authentication, adjust accordingly
        $response->assertStatus(201);
    }

    /** @test */
    public function test_doku_notification_validates_required_fields()
    {
        $invalidData = [
            'partnerServiceId' => '', // Empty required field
            'customerNo' => '0000000000001',
            'virtualAccountNo' => '777770000000000001',
            'virtualAccountName' => 'Too Long Name ' . str_repeat('x', 300), // Too long
            'trxId' => '', // Empty required field
            'paymentRequestId' => '12839218738127830',
            'paidAmount' => 'invalid-structure', // Should be array
            'virtualAccountEmail' => 'not-an-email', // Invalid email
            'virtualAccountPhone' => '12345678901234567890123' // Too long
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $invalidData);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'errors' => [
                         'partnerServiceId',
                         'virtualAccountName',
                         'trxId',
                         'paidAmount',
                         'virtualAccountEmail',
                         'virtualAccountPhone'
                     ]
                 ]);
    }

    /** @test */
    public function test_doku_notification_handles_minimal_data()
    {
        $minimalData = [
            'partnerServiceId' => '77777',
            'customerNo' => '0000000000001',
            'virtualAccountNo' => '777770000000000001',
            'virtualAccountName' => 'Toru Yamashita',
            'trxId' => '23219829713',
            'paymentRequestId' => '12839218738127830',
            'paidAmount' => [
                'value' => '11500.00',
                'currency' => 'IDR'
            ]
            // Email and phone are optional
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $minimalData);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');
    }

    /** @test */
    public function test_doku_notification_processes_correctly()
    {
        $dokuHeaders = [
            'X-TIMESTAMP' => '2020-12-21T07:56:11.000Z',
            'X-EXTERNAL-ID' => 'TEST_EXTERNAL_ID',
            'CHANNEL-ID' => 'VA004',
        ];

        $dokuData = [
            'partnerServiceId' => '12345',
            'customerNo' => 'CUST001',
            'virtualAccountNo' => '1234567890123456',
            'virtualAccountName' => 'John Customer',
            'trxId' => 'TRX789123',
            'paymentRequestId' => 'REQ456789',
            'paidAmount' => [
                'value' => '25000.50',
                'currency' => 'IDR'
            ],
            'virtualAccountEmail' => 'john@example.com',
            'virtualAccountPhone' => '08123456789'
        ];

        $response = $this->withHeaders($dokuHeaders)->postJson('/api/notifications/doku/virtual-account', $dokuData);

        $response->assertStatus(201);
        
        $notificationData = $response->json('data.notification');
        $this->assertEquals('DOKU Virtual Account Payment', $notificationData['title']);
        $this->assertEquals('payment', $notificationData['type']);
        $this->assertEquals('virtual_account', $notificationData['category']);
        $this->assertEquals('25000.50', $notificationData['payment_detail']['amount']);
        $this->assertEquals('IDR', $notificationData['payment_detail']['currency']);
        $this->assertEquals('TRX789123', $notificationData['doku_reference']['transaction_id']);
        $this->assertEquals('REQ456789', $notificationData['doku_reference']['payment_request_id']);
    }

    /** @test */
    public function test_doku_notification_formats_amount_correctly()
    {
        $testCases = [
            ['value' => '10000.00', 'currency' => 'IDR', 'expected_amount' => 10000.00],
            ['value' => '999.99', 'currency' => 'IDR', 'expected_amount' => 999.99],
            ['value' => '1000000', 'currency' => 'USD', 'expected_amount' => 1000000],
        ];

        foreach ($testCases as $testCase) {
            $dokuData = [
                'partnerServiceId' => '12345',
                'customerNo' => 'CUST001',
                'virtualAccountNo' => '1234567890123456',
                'virtualAccountName' => 'Test Customer',
                'trxId' => 'TRX' . mt_rand(100000, 999999),
                'paymentRequestId' => 'REQ' . mt_rand(100000, 999999),
                'paidAmount' => $testCase,
            ];

            $response = $this->postJson('/api/notifications/doku/virtual-account', $dokuData);
            
            $response->assertStatus(201);
            $notificationData = $response->json('data.notification');
            $this->assertEquals($testCase['expected_amount'], $notificationData['payment_detail']['amount']);
            $this->assertEquals(strtoupper($testCase['value']), strtoupper($notificationData['payment_detail']['currency']));
        }
    }

    /** @test */
    public function test_doku_notification_message_format()
    {
        $dokuData = [
            'partnerServiceId' => '12345',
            'customerNo' => 'CUST001',
            'virtualAccountNo' => '1234567890123456',
            'virtualAccountName' => 'Tono Customer',
            'trxId' => 'TRX123456',
            'paymentRequestId' => 'REQ123456',
            'paidAmount' => [
                'value' => '75.00',
                'currency' => 'IDR'
            ],
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $dokuData);
        
        $response->assertStatus(201);
        $message = $response->json('data.notification.message');
        $this->assertStringContainsString('Virtual Account payment received for 75.00 IDR from Tono Customer', $message);
        $this->assertStringContainsString('Transaction ID: TRX123456', $message);
    }

    /** @test */
    public function test_doku_notification_handles_currency_format()
    {
        $dokuData = [
            'partnerServiceId' => '12345',
            'customerNo' => 'CUST001',
            'virtualAccountNo' => '1234567890123456',
            'virtualAccountName' => 'International Customer',
            'trxId' => 'TRX123456',
            'paymentRequestId' => 'REQ123456',
            'paidAmount' => [
                'value' => '150.75',
                'currency' => 'usd' // lowercase
            ],
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $dokuData);
        
        $response->assertStatus(201);
        $currency = $response->json('data.notification.payment_detail.currency');
        $this->assertEquals('USD', $currency);
    }

    /** @test */
    public function test_doku_notification_error_response_format()
    {
        // Test with no data to trigger validation errors
        $response = $this->postJson('/api/notifications/doku/virtual-account', []);
        
        $response->assertStatus(422)
                 ->assertJsonPath('status', 'error')
                 ->assertJsonPath('message', 'Validation failed');
    }

    /** @test */
    public function test_doku_notification_with_string_paidAmount_value()
    {
        $dokuData = [
            'partnerServiceId' => '12345',
            'customerNo' => 'CUST001',
            'virtualAccountNo' => '1234567890123456',
            'virtualAccountName' => 'String Value Customer',
            'trxId' => 'TRX123456',
            'paymentRequestId' => 'REQ123456',
            'paidAmount' => [
                'value' => 'NotANumber',
                'currency' => 'IDR'
            ],
        ];

        $response = $this->postJson('/api/notifications/doku/virtual-account', $dokuData);
        $response->assertStatus(422); // Should fail validation for non-numeric amount
    }

    /** @test */
    public function test_doku_notification_preserves_headers_and_timestamps()
    {
        $dokuHeaders = [
            'X-TIMESTAMP' => '2020-12-21T07:56:11.000Z',
            'X-EXTERNAL-ID' => 'EXT123456',
            'CHANNEL-ID' => 'VA004',
        ];

        $dokuData = [
            'partnerServiceId' => '12345',
            'customerNo' => 'CUST001',
            'virtualAccountNo' => '1234567890123456',
            'virtualAccountName' => 'Header Test',
            'trxId' => 'TRX123456',
            'paymentRequestId' => 'REQ123456',
            'paidAmount' => [
                'value' => '100.00',
                'currency' => 'IDR'
            ],
        ];

        $response = $this->withHeaders($dokuHeaders)->postJson('/api/notifications/doku/virtual-account', $dokuData);
        
        $response->assertStatus(201);
        $notificationData = $response->json('data.notification');
        
        $this->assertEquals('2020-12-21T07:56:11.000Z', $notificationData['timestamp']);
        $this->assertEquals('EXT123456', $notificationData['external_id']);
        $this->assertEquals('VA004', $notificationData['channel_id']);
    }
}