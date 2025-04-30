<?php

namespace App\Http\Controllers\room;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoomController extends Controller {
    public function index(){
        return view("pages.room.index");
    }

    // Show rooms for a specific property
    public function showPropertyRooms($propertyId)
    {
        try {
            // Find property and eager load rooms
            $property = Property::with('rooms')->findOrFail($propertyId);
            
            // Format the rooms data
            $formattedRooms = $property->rooms->map(function($room) {
                return [
                    'id' => $room->idrec,
                    'name' => $room->name,
                    'type' => $room->type,
                    'descriptions' => $room->descriptions,
                    'facility' => is_string($room->facility) ? json_decode($room->facility, true) : ($room->facility ?? []),
                    'attachment' => is_string($room->attachment) ? json_decode($room->attachment, true) : ($room->attachment ?? []),
                    'status' => $room->status
                ];
            });

            // Format property data (using your existing format)
            $formattedHouse = [
                'id' => $property->idrec,
                'name' => $property->name,
                'type' => $property->tags,
                'location' => $property->address,
                'subLocation' => $property->subdistrict . ', ' . $property->city,
                'distance' => $property->distance ? "{$property->distance} km dari {$property->location}" : null,
                'price' => [
                    'original' => $property->price['original'] ?? 0,
                    'discounted' => $property->price['discounted'] ?? 0
                ],
                'features' => is_string($property->features) ? json_decode($property->features, true) : ($property->features ?? []),
                'image' => $property->image,
                'image_2' => $property->image_2 ?? null,
                'image_3' => $property->image_3 ?? null,
                'attributes' => is_string($property->attributes) ? json_decode($property->attributes, true) : ($property->attributes ?? [
                    'amenities' => [],
                    'room_facilities' => [],
                    'rules' => []
                ]),
                'description' => $property->description,
                'address' => [
                    'province' => $property->province,
                    'city' => $property->city,
                    'subdistrict' => $property->subdistrict,
                    'village' => $property->village,
                    'postal_code' => $property->postal_code,
                    'full_address' => $property->address
                ],
                'status' => $property->status,
                'rooms' => $formattedRooms // Add the formatted rooms data
            ];

            return view('pages.house.show', [
                'house' => $formattedHouse
            ]);

        } catch (Exception $e) {
            Log::error('Property not found or error occurred: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Property not found');
        }
    }

    // Store a new room for a property
    public function store(Request $request, $propertyId)
    {
        try {
            $property = Property::findOrFail($propertyId);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'descriptions' => 'nullable|string',
                'type' => 'required|string|max:50',
                'facility' => 'nullable|array',
                'attachment' => 'nullable|array',
                'status' => 'required|string|in:active,inactive'
            ]);

            $room = $property->rooms()->create([
                'property_name' => $property->name,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'descriptions' => $validated['descriptions'],
                'type' => $validated['type'],
                'facility' => json_encode($validated['facility'] ?? []),
                'attachment' => json_encode($validated['attachment'] ?? []),
                'status' => $validated['status'],
                'created_by' => Auth::user()->name ?? 'admin',
                'updated_by' => Auth::user()->name ?? 'admin'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Room created successfully',
                'data' => $room
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update an existing room
    public function update(Request $request, $id)
    {
        try {
            $room = Room::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'descriptions' => 'nullable|string',
                'type' => 'sometimes|string|max:50',
                'facility' => 'nullable|array',
                'attachment' => 'nullable|array',
                'status' => 'sometimes|string|in:active,inactive'
            ]);

            $updateData = [
                ...$validated,
                'updated_by' => Auth::user()->name ?? 'admin',
                'updated_at' => Carbon::now()
            ];

            if (isset($validated['facility'])) {
                $updateData['facility'] = json_encode($validated['facility']);
            }

            if (isset($validated['attachment'])) {
                $updateData['attachment'] = json_encode($validated['attachment']);
            }

            if (isset($validated['name'])) {
                $updateData['slug'] = Str::slug($validated['name']);
            }

            $room->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Room updated successfully',
                'data' => $room
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a room
    public function destroy($id)
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();

            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($slug)
    {
        try {
            // Find the room by slug and eager load the property relationship
            $room = Room::with('property')->where('slug', $slug)->firstOrFail();
            
            // Format the room data
            $formattedRoom = [
                'id' => $room->idrec,
                'property_id' => $room->property_id,
                'property_name' => $room->property_name,
                'slug' => $room->slug,
                'name' => $room->name,
                'descriptions' => $room->descriptions,
                'type' => $room->type,
                'level' => $room->level,
                'facility' => is_string($room->facility) ? json_decode(
                    $room->facility, true
                    ) : (
                        $room->facility ?? []
                    ),
                'attachment' => is_string($room->attachment) ? json_decode(
                    $room->attachment, true
                    ) : (
                        $room->attachment ?? []
                    ),
                'property' => [
                    'id' => $room->property->idrec,
                    'name' => $room->property_name,
                    'location' => $room->property->address,
                    'subLocation' => $room->property->subdistrict . ', ' . $room->property->city,
                ],
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at,
                'created_by' => $room->created_by,
                'updated_by' => $room->updated_by,
                'status' => $room->status
            ];

            return view('pages.room.show', [
                'room' => $formattedRoom
            ]);

        } catch (Exception $e) {
            Log::error('Room not found or error occurred: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Room not found');
        }
    }

    public function book(Request $request)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:2',
        ]);

        // Add your booking logic here

        return redirect()->back()->with('success', 'Booking request submitted successfully!');
    }
}