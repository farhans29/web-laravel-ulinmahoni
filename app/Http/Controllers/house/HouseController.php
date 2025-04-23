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
            // Get properties that are houses from the database
            $query = Property::query()->whereJsonContains('tags', 'House');
            
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

            // Get house from database (using Property model and filtering for houses)
            $house = Property::where('tags', 'House')->findOrFail($id);

            // Format the data for the view
            $formattedHouse = [
                'id' => is_numeric($house->idrec) ? $house->idrec : null,
                'name' => is_string($house->name) ? $house->name : null,
                'type' => is_array($house->tags) && in_array('House', $house->tags) ? 'House' : 'Other',
                'location' => is_string($house->address) ? $house->address : null,
                'distance' => is_string($house->location) ? $house->distance : null,
                'price' => [
                    'original' => [
                        '1_month' => isset($house->price['original']) && is_numeric($house->price['original']) ? 
                            $house->price['original'] : null,
                        '6_month' => isset($house->price['original_6']) && is_numeric($house->price['original_6']) ? 
                            $house->price['original_6'] : null,
                        '12_month' => isset($house->price['original_12']) && is_numeric($house->price['original_12']) ? 
                            $house->price['original_12'] : null,
                    ],
                    'discounted' => [
                        '1_month' => isset($house->price['discounted']) && is_numeric($house->price['discounted']) ? 
                            $house->price['discounted'] : null,
                        '6_month' => isset($house->price['discounted_6']) && is_numeric($house->price['discounted_6']) ? 
                            $house->price['discounted_6'] : null,
                        '12_month' => isset($house->price['discounted_12']) && is_numeric($house->price['discounted_12']) ? 
                            $house->price['discounted_12'] : null,
                    ]
                ],
                'features' => is_array($house->features) ? $house->features : [],
                'image' => is_string($house->image) ? $house->image : null,
                'image_2' => is_string($house->image_2) ? $house->image_2 : null,
                'image_3' => is_string($house->image_3) ? $house->image_3 : null,
                'attributes' => is_array($house->attributes) ? $house->attributes : [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ],
                'description' => is_string($house->description) ? $house->description : null,
                'address' => [
                    'province' => is_string($house->province) ? $house->province : null,
                    'city' => is_string($house->city) ? $house->city : null,
                    'subdistrict' => is_string($house->subdistrict) ? $house->subdistrict : null,
                    'village' => is_string($house->village) ? $house->village : null,
                    'postal_code' => is_string($house->postal_code) ? $house->postal_code : null,
                    'full_address' => is_string($house->address) ? $house->address : null
                ],
                'status' => is_string($house->status) ? $house->status : null
            ];

            return view('pages.house.show', [
                'house' => $formattedHouse,
                'dummyHouse' => $dummyHouse // Keeping dummy data for reference
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
            $query = Property::query()->whereJsonContains('tags', 'House');

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