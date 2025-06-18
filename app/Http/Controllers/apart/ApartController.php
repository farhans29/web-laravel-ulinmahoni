<?php

namespace App\Http\Controllers\apart;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class ApartController extends Controller {
    public function index(Request $request){
        try {
            // Get apartments from the database
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

            // Filter for apartments
            $query->where('tags', 'Apartment');

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $apartments = $query->paginate(12); // Show 12 apartments per page

            return view("pages.apartment.index", compact('apartments'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading apartments: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Get apartment from database (using Property model and filtering for apartments)
            $apartment = Property::where('tags', 'Apartment')->findOrFail($id);

            // Format the data for the view
            $formattedApartment = [
                'id' => $apartment->idrec,
                'name' => $apartment->name,
                'type' => $apartment->tags,
                'location' => $apartment->address,
                'subLocation' => $apartment->subdistrict . ', ' . $apartment->city,
                'distance' => $apartment->distance ? "{$apartment->distance} km dari {$apartment->location}" : null,
                'price' => [
                    'original' => $apartment->price['original'] ?? 0,
                    'discounted' => $apartment->price['discounted'] ?? 0
                ],
                'features' => is_string($apartment->features) ? json_decode($apartment->features, true) : ($apartment->features ?? []),
                'image' => $apartment->image,
                'image_2' => $apartment->image_2 ?? null,
                'image_3' => $apartment->image_3 ?? null,
                'attributes' => is_string($apartment->attributes) ? json_decode($apartment->attributes, true) : ($apartment->attributes ?? [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ]),
                'description' => $apartment->description,
                'address' => [
                    'province' => $apartment->province,
                    'city' => $apartment->city,
                    'subdistrict' => $apartment->subdistrict,
                    'village' => $apartment->village,
                    'postal_code' => $apartment->postal_code,
                    'full_address' => $apartment->address
                ],
                'status' => $apartment->status
            ];

            return view('pages.apartment.show', [
                'apartment' => $formattedApartment
            ]);

        } catch (Exception $e) {
            Log::error('Apartment not found or error occurred: ' . $e->getMessage());
            abort(404);
        }
    }

    /**
     * Search apartments with filters
     */
    public function search(Request $request)
    {
        try {
            $query = Property::query()->where('tags', 'Apartment');

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
            $apartments = $query->paginate(12)->withQueryString();

            return view('pages.apartment.index', compact('apartments'));
        } catch (Exception $e) {
            return back()->with('error', 'Error searching apartments: ' . $e->getMessage());
        }
    }
}