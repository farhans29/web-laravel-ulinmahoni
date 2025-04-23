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
            // Get active properties (status = 1)
            $properties = Property::where('status', 1)
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
                $tag = $property->tags; // Direct string comparison
                
                if (array_key_exists($tag, $propertyTypes)) {
                    $propertyTypes[$tag][] = $this->formatProperty($property);
                }
            }

            // Log the final data for debugging
            Log::info('Property data by type:', [
                'houses_count' => count($propertyTypes['House']),
                'apartments_count' => count($propertyTypes['Apartment']),
                'villas_count' => count($propertyTypes['Villa']),
                'hotels_count' => count($propertyTypes['Hotel'])
            ]);

            return view("pages.homepage.index", [
                'houses' => $propertyTypes['House'],
                'apartments' => $propertyTypes['Apartment'],
                'villas' => $propertyTypes['Villa'],
                'hotels' => $propertyTypes['Hotel'],
                'heroMedia' => $heroMedia
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching properties: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
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
        // Handle price data
        $price = is_string($property->price) ? json_decode($property->price, true) : $property->price;
        if (!is_array($price)) {
            $price = [
                'original' => $price ?? 0,
                'discounted' => $price ?? 0
            ];
        }

        // Handle features data
        $features = is_string($property->features) ? json_decode($property->features, true) : $property->features;
        if (!is_array($features)) {
            $features = [];
        }

        return [
            'id' => $property->idrec,
            'name' => $property->name,
            'type' => $property->tags,
            'location' => $property->address,
            'subLocation' => $property->subdistrict . ', ' . $property->city,
            'distance' => $property->distance ? "{$property->distance} km dari {$property->location}" : null,
            'price' => [
                'original' => $price['original'] ?? 0,
                'discounted' => $price['discounted'] ?? 0
            ],
            'features' => $features,
            'image' => $property->image,
            'status' => $property->status
        ];
    }

    public function comingSoon()
    {
        return view("pages.coming-soon.index");
    }
}