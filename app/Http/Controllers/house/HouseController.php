<?php

namespace App\Http\Controllers\House;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class HouseController extends Controller {
    public function index(Request $request){
        try {
            // Get houses from the database
            $query = Property::where('status', 1);
            
            // Add filters if provided in the request
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('province')) {
                $query->where('province', $request->province);
            }

            if ($request->has('city')) {
                $query->where('city', $request->city);
            }

            if ($request->has('price_min')) {
                $query->where('price->original', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('price->original', '<=', $request->price_max);
            }

            // Filter for houses
            $query->where('tags', 'House');

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $houses = $query->paginate(12); // Show 12 houses per page

            return view("pages.house.index", compact('houses'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading houses: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            /*
            // DONT REMOVE THIS COMMENTED LINE DUMMY DATAS
            // For now, we'll use dummy data. Later you can replace this with actual database queries
            $dummyHouse = [
                'id' => 1,
                'name' => 'Rexucia House & Room',
                'type' => 'Coliving',
                'location' => 'Petojo Selatan, Gambir',
                'distance' => '2.4 km dari Stasiun MRT Bundaran HI',
                'price' => [
                    'original' => [
                        '1_month' => 1300000,
                        '6_month' => 0,
                        '12_month' => 0,
                    ],
                    'discounted' => [
                        '1_month' => 975000,
                        '6_month' => 0,
                        '12_month' => 0,
                    ]
                ],
                'features' => [
                    'Diskon sewa 12 Bulan',
                    'S+ Voucher s.d. 2%'
                ],
                'image' => 'images/assets/foto_project_ulin_mahoni/RENDER HALUS PROJECT BAPAK LIU KOS BOGOR_pages-to-jpg-0006.jpg',
                'attributes' => [
                    'amenities' => [
                        'High-speed WiFi',
                        '24/7 Security',
                        'Shared Kitchen',
                        'Laundry Service',
                        'Parking Area',
                        'Common Area'
                    ],
                    'room_facilities' => [
                        'Air Conditioning',
                        'Private Bathroom',
                        'Furnished',
                        'TV Cable Ready'
                    ],
                    'rules' => [
                        'No Smoking',
                        'No Pets',
                        'ID Card Required',
                        'Deposit Required'
                    ]
                ],
                'description' => 'Experience modern coliving at its finest in this strategically located property. 
                                Featuring well-designed spaces, community areas, and all the amenities you need 
                                for comfortable urban living. Perfect for young professionals and students looking 
                                for a vibrant community in the heart of the city.'
            ];
            // End of dummy data
            */

            // Get house from database (using Property model and filtering for houses)
            $house = Property::where('tags', 'House')->findOrFail($id);

            // Format the data for the view
            $formattedHouse = [
                'id' => $house->idrec,
                'name' => $house->name,
                'type' => $house->tags,
                'location' => $house->address,
                'subLocation' => $house->subdistrict . ', ' . $house->city,
                'distance' => $house->distance ? "{$house->distance} km dari {$house->location}" : null,
                'price' => [
                    'original' => $house->price['original'] ?? 0,
                    'discounted' => $house->price['discounted'] ?? 0
                ],
                'features' => is_string($house->features) ? json_decode($house->features, true) : ($house->features ?? []),
                'image' => $house->image,
                'image_2' => $house->image_2 ?? null,
                'image_3' => $house->image_3 ?? null,
                'attributes' => is_string($house->attributes) ? json_decode($house->attributes, true) : ($house->attributes ?? [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ]),
                'description' => $house->description,
                'address' => [
                    'province' => $house->province,
                    'city' => $house->city,
                    'subdistrict' => $house->subdistrict,
                    'village' => $house->village,
                    'postal_code' => $house->postal_code,
                    'full_address' => $house->address
                ],
                'status' => $house->status
            ];

            return view('pages.house.show', [
                'house' => $formattedHouse
            ]);

        } catch (Exception $e) {
            Log::error('House not found or error occurred: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Search houses with filters
     */
    public function search(Request $request)
    {
        try {
            $query = Property::query()->where('tags', 'House');

            // Apply search filters
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            // Apply location filters
            if ($request->has('province')) {
                $query->where('province', $request->province);
            }
            if ($request->has('city')) {
                $query->where('city', $request->city);
            }

            // Apply price range filter
            if ($request->has('price_min')) {
                $query->where('price->original', '>=', $request->price_min);
            }
            if ($request->has('price_max')) {
                $query->where('price->original', '<=', $request->price_max);
            }

            // Apply status filter
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $houses = $query->paginate(12)->withQueryString();

            return view('pages.house.index', compact('houses'));
        } catch (Exception $e) {
            return back()->with('error', 'Error searching houses: ' . $e->getMessage());
        }
    }
}