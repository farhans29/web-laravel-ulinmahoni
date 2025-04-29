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

class RoomController extends Controller {
    public function index(){
        return view("pages.room.index");
    }

    // Show rooms for a specific property
    public function showPropertyRooms($propertyId)
    {
        try {
            $house = Property::with('rooms')->findOrFail($propertyId);
            return view('pages.house.show', compact('house'));
        } catch (Exception $e) {
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
}