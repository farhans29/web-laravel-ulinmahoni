<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Room;
use Illuminate\Http\Request;


class RoomController extends ApiController
{
    // GET /api/rooms/property/{property_id}
    public function byPropertyId($property_id)
    {
        $rooms = Room::where('property_id', $property_id)->get();
        return response()->json($rooms);
    }
    // GET /api/v1/rooms
    public function index(Request $request)
    {
        try {
            $query = Room::query();
            
            // Join with room images
            $query->leftJoin('m_room_images', 'm_room_images.room_id', '=', 'm_rooms.idrec')
                ->select([
                    'm_rooms.*',
                    'm_room_images.idrec as image_id',
                    'm_room_images.image as image_data',
                    'm_room_images.caption',
                ]);

            // Add filters if provided
            if ($request->has('idrec')) {
                $query->where('m_rooms.idrec', $request->idrec);
            }

            $rooms = $query->get();
            
            // Group images by room
            $groupedRooms = $rooms->groupBy('idrec')->map(function ($roomGroup) {
                $room = $roomGroup->first();
                $images = $roomGroup->filter(function ($item) {
                    return $item->image_id !== null;
                })->map(function ($imageItem) {
                    return [
                        'id' => $imageItem->image_id,
                        'image_data' => $imageItem->image_data,
                        'caption' => $imageItem->caption,
                    ];
                })->values();
                
                $roomArray = $room->toArray();
                $roomArray['images'] = $images;
                
                // Remove image-related fields from the main room object
                unset(
                    $roomArray['image_id'],
                    $roomArray['image_data'],
                    $roomArray['caption']
                );
                
                return $roomArray;
            })->values();

            return $this->respond([
                'data' => $groupedRooms
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    // GET /api/v1/rooms/{id}
    public function show($id)
    {
        try {
            $room = Room::leftJoin('m_room_images', 'm_room_images.room_id', '=', 'm_rooms.idrec')
                ->where('m_rooms.idrec', $id)
                ->select([
                    'm_rooms.*',
                    'm_room_images.idrec as image_id',
                    'm_room_images.image as image_data',
                    'm_room_images.caption',
                ])
                ->get();
            
            if ($room->isEmpty()) {
                return $this->respondNotFound('Room not found');
            }
            
            // Group images by room
            $groupedRoom = $room->groupBy('idrec')->map(function ($roomGroup) {
                $room = $roomGroup->first();
                $images = $roomGroup->filter(function ($item) {
                    return $item->image_id !== null;
                })->map(function ($imageItem) {
                    return [
                        'id' => $imageItem->image_id,
                        'image_data' => $imageItem->image_data,
                        'caption' => $imageItem->caption,
                    ];
                })->values();
                
                $roomArray = $room->toArray();
                $roomArray['images'] = $images;
                
                // Remove image-related fields from the main room object
                unset(
                    $roomArray['image_id'],
                    $roomArray['image_data'],
                    $roomArray['caption']
                );
                
                return $roomArray;
            })->first();
            
            return $this->respond([
                'data' => $groupedRoom
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
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
