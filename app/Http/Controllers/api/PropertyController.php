<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\apiController;
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
            $query->orderBy('created_at', 'desc');
            
            // Check if pagination is requested
            if ($request->has('limit') && $request->has('page')) {
                $properties = $query->paginate($request->limit);
                
                return $this->respondWithPagination($properties, [
                    'data' => $properties->items()
                ]);
            }
            
            // Get all properties
            $properties = $query->get();
            
            return $this->respond([
                'data' => $properties
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
            
            return $this->respondCreated([
                'message' => 'Property created successfully',
                'created_id' => $property->idrec
            ]);
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
