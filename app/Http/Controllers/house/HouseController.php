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

            // Get paginated results and transform the data
            $houses = $query->paginate(12)->through(function($house) {
                return [
                    'id' => $house->idrec,
                    'slug' => $house->slug,
                    'name' => $house->name,
                    'type' => $house->tags,
                    'subLocation' => $house->subdistrict . ', ' . $house->city,
                    'distance' => $house->distance ? "{$house->distance} km dari {$house->location}" : null,
                    'price' => [
                        'original' => json_decode($house->price)->original ?? 0,
                        'discounted' => json_decode($house->price)->discounted ?? 0
                    ],
                    'features' => is_string($house->features) ? json_decode($house->features, true) : [],
                    'image' => $house->image
                ];
            });

            return view("pages.house.index", compact('houses'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading houses: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Raw query to get property with its rooms
            $house = DB::select("
                SELECT 
                    p.*,
                    r.idrec as room_id,
                    r.property_id,
                    r.property_name,
                    r.slug as room_slug,
                    r.name as room_name,
                    r.descriptions as room_descriptions,
                    r.type as room_type,
                    r.level as room_level,
                    r.facility as room_facility,
                    r.attachment as room_attachment,
                    r.created_at as room_created_at,
                    r.updated_at as room_updated_at,
                    r.created_by as room_created_by,
                    r.updated_by as room_updated_by,
                    r.status as room_status
                FROM t_properties p
                LEFT JOIN t_rooms r ON p.idrec = r.property_id
                WHERE p.tags = 'House' 
                AND p.idrec = ?
                ORDER BY idrec ASC
            ", [$id]);

            if (empty($house)) {
                throw new Exception('House not found');
            }

            // Get the first row for house data (since it's repeated in the join)
            $houseData = $house[0];

            // Format rooms data
            $formattedRooms = collect($house)->map(function($row) {
                if ($row->room_id) {
                    return [
                        'id' => $row->room_id,
                        'property_id' => $row->property_id,
                        'property_name' => $row->property_name,
                        'slug' => $row->room_slug,
                        'name' => $row->room_name,
                        'descriptions' => $row->room_descriptions,
                        'type' => $row->room_type,
                        'level' => $row->room_level,
                        'facility' => is_string($row->room_facility) ? json_decode($row->room_facility, true) : [],
                        'attachment' => is_string($row->room_attachment) ? json_decode($row->room_attachment, true) : [],
                        'created_at' => $row->room_created_at,
                        'updated_at' => $row->room_updated_at,
                        'created_by' => $row->room_created_by,
                        'updated_by' => $row->room_updated_by,
                        'status' => $row->room_status
                    ];
                }
            })->filter();

            // Format the data for the view
            $formattedHouse = [
                'id' => $houseData->idrec,
                'slug' => $houseData->slug,
                'name' => $houseData->name,
                'type' => $houseData->tags,
                'location' => $houseData->address,
                'subLocation' => $houseData->subdistrict . ', ' . $houseData->city,
                'distance' => $houseData->distance ? "{$houseData->distance} km dari {$houseData->location}" : null,
                'price' => [
                    'original' => json_decode($houseData->price)->original ?? 0,
                    'discounted' => json_decode($houseData->price)->discounted ?? 0
                ],
                'features' => is_string($houseData->features) ? json_decode($houseData->features, true) : [],
                'image' => $houseData->image,
                'image_2' => $houseData->image_2 ?? null,
                'image_3' => $houseData->image_3 ?? null,
                'attributes' => is_string($houseData->attributes) ? json_decode($houseData->attributes, true) : [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ],
                'description' => $houseData->description,
                'address' => [
                    'province' => $houseData->province,
                    'city' => $houseData->city,
                    'subdistrict' => $houseData->subdistrict,
                    'village' => $houseData->village,
                    'postal_code' => $houseData->postal_code,
                    'full_address' => $houseData->address
                ],
                'status' => $houseData->status,
                'rooms' => $formattedRooms
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
            $query->orderBy('idrec', 'asc');

            // Get paginated results
            $houses = $query->paginate(12)->withQueryString();

            return view('pages.house.index', compact('houses'));
        } catch (Exception $e) {
            return back()->with('error', 'Error searching houses: ' . $e->getMessage());
        }
    }
}