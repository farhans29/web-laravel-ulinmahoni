<?php

namespace App\Http\Controllers\property;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Property;

class PropertyController extends Controller {
    public function index(Request $request){
        try {
            // Get properties from the database
            $query = Property::query();
            
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
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $properties = $query->paginate(12); // Show 12 properties per page

            return view("pages.property.index", compact('properties'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading properties: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // DONT REMOVE THIS COMMENTED LINE DUMMY DATAS
            // For now, we'll use dummy data. Later you can replace this with actual database queries
            $dummyProperty = [
                'id' => 1,
                'name' => 'Rexucia',
                'type' => 'Coliving',
                'location' => 'Petojo Selatan, Gambir',
                'distance' => '2.4 km dari Stasiun MRT Bundaran HI',
                'price' => [
                    'original' => 1300000,
                    'discounted' => 975000
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

            // Get property from database
            $property = Property::findOrFail($id);

            // Format the data for the view
            $formattedProperty = [
                'id' => $property->idrec,
                'name' => $property->name,
                'location' => $property->address,
                'distance' => $property->distance . ' km dari ' . $property->location,
                'price' => is_array($property->price) ? $property->price : ['original' => $property->price],
                'features' => $property->features ?? [],
                'image' => $property->image,
                'attributes' => $property->attributes ?? [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ],
                'description' => $property->description,
                'address' => [
                    'province' => $property->province,
                    'city' => $property->city,
                    'subdistrict' => $property->subdistrict,
                    'village' => $property->village,
                    'postal_code' => $property->postal_code,
                    'full_address' => $property->address
                ],
                'tags' => $property->tags ?? [],
                'status' => $property->status
            ];

            return view('pages.property.show', [
                'property' => $formattedProperty,
                'dummyProperty' => $dummyProperty // Keeping dummy data for reference
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Property not found or error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Search properties with filters
     */
    public function search(Request $request)
    {
        try {
            $query = Property::query();

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
                $query->where('price', '>=', $request->price_min);
            }
            if ($request->has('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            // Apply tags filter
            if ($request->has('tags')) {
                $tags = is_array($request->tags) ? $request->tags : [$request->tags];
                foreach ($tags as $tag) {
                    $query->whereJsonContains('tags', $tag);
                }
            }

            // Apply status filter
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $properties = $query->paginate(12)->withQueryString();

            return view('pages.property.index', compact('properties'));
        } catch (Exception $e) {
            return back()->with('error', 'Error searching properties: ' . $e->getMessage());
        }
    }
}