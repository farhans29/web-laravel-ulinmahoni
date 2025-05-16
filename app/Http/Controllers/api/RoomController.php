<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET /api/rooms/property/{property_id}
    public function byPropertyId($property_id)
    {
        $rooms = Room::where('property_id', $property_id)->get();
        return response()->json($rooms);
    }
    // GET /api/v1/rooms
    public function index()
    {
        return response()->json(Room::all());
    }

    // GET /api/v1/rooms/{id}
    public function show($id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        return response()->json($room);
    }

    // POST /api/v1/rooms
    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|integer',
            'property_name' => 'required|string',
            'name' => 'required|string',
            'slug' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'type' => 'nullable|string',
            'level' => 'nullable|string',
            'facility' => 'nullable|array',
            'attachment' => 'nullable|array',
            'periode' => 'nullable|array',
            'status' => 'nullable|string',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
            'price' => 'nullable|array'
        ]);
        $room = Room::create($data);
        return response()->json($room, 201);
    }

    // PUT/PATCH /api/v1/rooms/{id}
    public function update(Request $request, $id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        $data = $request->validate([
            'property_id' => 'sometimes|integer',
            'property_name' => 'sometimes|string',
            'name' => 'sometimes|string',
            'slug' => 'nullable|string',
            'descriptions' => 'nullable|string',
            'type' => 'nullable|string',
            'level' => 'nullable|string',
            'facility' => 'nullable|array',
            'attachment' => 'nullable|array',
            'periode' => 'nullable|array',
            'status' => 'nullable|string',
            'created_by' => 'nullable|integer',
            'updated_by' => 'nullable|integer',
            'price' => 'nullable|array'
        ]);
        $room->update($data);
        return response()->json($room);
    }

    // DELETE /api/v1/rooms/{id}
    public function destroy($id)
    {
        $room = Room::find($id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        $room->delete();
        return response()->json(['message' => 'Room deleted']);
    }
}
