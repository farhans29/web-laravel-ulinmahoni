<?php

namespace App\Http\Controllers\homepage;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class HomeController extends Controller {
    
    public function index()
    {
        $heroMedia = [
            'type' => 'video',
            'sources' => [
                'image' => 'images/assets/pics/WhatsApp Image 2025-02-20 at 14.30.45.jpeg',
                'video' => 'images/assets/My_Movie.mp4'
            ]
        ];

        try {
            // Get active properties
            $properties = Property::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Prepare property data by type
            $propertyTypes = [
                'House' => [],
                'Apartment' => [],
                'Villa' => [],
                'Hotel' => []
            ];

            foreach ($properties as $property) {
                $tags = is_array($property->tags) ? $property->tags : [];
                
                foreach ($propertyTypes as $type => $data) {
                    if (in_array($type, $tags)) {
                        $propertyTypes[$type][] = $this->formatProperty($property);
                    }
                }
            }

            return view("pages.homepage.index", [
                'houses' => $propertyTypes['House'],
                'apartments' => $propertyTypes['Apartment'],
                'villas' => $propertyTypes['Villa'],
                'hotels' => $propertyTypes['Hotel'],
                'heroMedia' => $heroMedia
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching properties: ' . $e->getMessage());
            
            return view("pages.homepage.index", [
                'houses' => [],
                'apartments' => [],
                'villas' => [],
                'hotels' => [],
                'heroMedia' => $heroMedia
            ]);
        }
    }

    private function formatProperty($property)
    {
        return [
            'id' => $property->idrec,
            'name' => $property->name,
            'type' => is_array($property->tags) ? $property->tags[0] : 'Other',
            'location' => $property->address,
            'subLocation' => $property->subdistrict . ', ' . $property->city,
            'distance' => $property->distance ? "{$property->distance} km dari {$property->location}" : null,
            'price' => [
                'original' => $property->price['original'] ?? 0,
                'discounted' => $property->price['discounted'] ?? 0
            ],
            'features' => $property->features ?? [],
            'image' => $property->image,
            'status' => $property->status
        ];
    }

    public function comingSoon()
    {
        return view("pages.coming-soon.index");
    }
}