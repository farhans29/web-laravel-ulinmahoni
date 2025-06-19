<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends ApiController
{
    /**
     * Get all properties with optional filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Property::query();
            
            // Join with property images
            $query->leftJoin('m_property_images', 'm_property_images.property_id', '=', 'm_properties.idrec')
                ->select([
                    'm_properties.*',
                    'm_property_images.idrec as image_id',
                    'm_property_images.image',
                    'm_property_images.caption',
                ]);

            
            // Add filters if provided
            if ($request->has('tags')) {
                $query->where('tags', $request->tags);
            }

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
            $query->orderBy('m_properties.created_at', 'desc');
            
            // Get all properties with their images
            $properties = $query->get();
            
            // Group images by property
            $groupedProperties = $properties->groupBy('idrec')->map(function ($propertyGroup) {
                $property = $propertyGroup->first();
                $images = $propertyGroup->filter(function ($item) {
                    return $item->image_id !== null;
                })->map(function ($imageItem) {
                    return [
                        'id' => $imageItem->image_id,
                        'image' => $imageItem->image,
                        'caption' => $imageItem->caption,
                    ];
                })->values();
                
                $propertyArray = $property->toArray();
                $propertyArray['images'] = $images;
                
                // Remove image-related fields from the main property object
                unset(
                    $propertyArray['image_id'],
                    $propertyArray['image'],
                    $propertyArray['caption']
                );
                
                return $propertyArray;
            })->values();
            
            // Handle pagination if requested
            if ($request->has('limit') && $request->has('page')) {
                $page = $request->page;
                $perPage = $request->limit;
                $offset = ($page - 1) * $perPage;
                
                $paginatedItems = $groupedProperties->slice($offset, $perPage)->values();
                
                $response = new \Illuminate\Pagination\LengthAwarePaginator(
                    $paginatedItems,
                    $groupedProperties->count(),
                    $perPage,
                    $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                
                return $this->respondWithPagination($response, [
                    'data' => $response->items()
                ]);
            }
            
            return $this->respond([
                'data' => $groupedProperties
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
    
    /**
     * Get a specific property by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $property = Property::find($id);
            
            if (!$property) {
                return $this->respondNotFound('Property not found');
            }
            
            return $this->respond([
                'data' => $property
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
    
    /**
     * Create a new property
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'tags' => 'nullable|array',
                'province' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'subdistrict' => 'required|string|max:100',
                'village' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'address' => 'required|string',
                'location' => 'required|string',
                'distance' => 'nullable|numeric',
                'price' => 'required|numeric',
                'features' => 'nullable|array',
                'attributes' => 'nullable|array',
                'image' => 'required|string',
                'status' => 'required|in:active,inactive',
                'created_by' => 'required|integer'
            ]);
            
            $property = Property::create($validated);
            
            return response()->json([
                'message' => 'Property created successfully',
                'created_id' => $property->idrec
            ], 201);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
    
    /**
     * Update an existing property
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $property = Property::find($id);
            
            if (!$property) {
                return $this->respondNotFound('Property not found');
            }
            
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'tags' => 'nullable|array',
                'province' => 'sometimes|string|max:100',
                'city' => 'sometimes|string|max:100',
                'subdistrict' => 'sometimes|string|max:100',
                'village' => 'sometimes|string|max:100',
                'postal_code' => 'sometimes|string|max:10',
                'address' => 'sometimes|string',
                'location' => 'sometimes|string',
                'distance' => 'nullable|numeric',
                'price' => 'sometimes|numeric',
                'features' => 'nullable|array',
                'attributes' => 'nullable|array',
                'image' => 'sometimes|string',
                'status' => 'sometimes|in:active,inactive',
                'updated_by' => 'required|integer'
            ]);
            
            $property->update($validated);
            
            return $this->respond([
                'message' => 'Property updated successfully',
                'data' => $property
            ]);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
    
    /**
     * Delete a property
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $property = Property::find($id);
            
            if (!$property) {
                return $this->respondNotFound('Property not found');
            }
            
            $property->delete();
            
            return $this->respondDeleted('Property deleted successfully');
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}
