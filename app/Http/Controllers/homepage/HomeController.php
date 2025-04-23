<?php

namespace App\Http\Controllers\homepage;

/**
 * HomeController manages the main homepage of the Ulin Mahoni property website.
 * 
 * This controller is responsible for:
 * - Displaying all property types (Houses, Apartments, Villas, and Hotels)
 * - Managing the hero section with video/image support
 * - Formatting and categorizing property data for display
 * - Error handling and logging for property data retrieval
 * 
 * @package App\Http\Controllers\homepage
 */

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Property;

class HomeController extends Controller {
    /**
     * Display the homepage with categorized properties and hero media.
     * 
     * This method:
     * - Sets up the hero media section (video/image)
     * - Fetches all active properties from the database
     * - Categorizes properties by type (House, Apartment, Villa, Hotel)
     * - Formats property data for display
     * - Handles and logs any errors that occur during data retrieval
     * 
     * @return \Illuminate\View\View Returns the homepage view with formatted property data
     */
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

    /**
     * Format a property model instance into a standardized array structure.
     * 
     * @param Property $property The property model instance to format
     * @return array Formatted property data with the following structure:
     *               - id: int (Property ID)
     *               - name: string (Property name)
     *               - type: string (Property type - House/Apartment/Villa/Hotel)
     *               - location: string (Full address)
     *               - subLocation: string (Subdistrict and city)
     *               - distance: string|null (Distance from landmark)
     *               - price: array (Original and discounted prices)
     *               - features: array (Property features)
     *               - image: string (Base64 encoded image)
     *               - status: int (Property status)
     */
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

    /**
     * Display the coming soon page.
     * 
     * @return \Illuminate\View\View Returns the coming soon page view
     */
    public function comingSoon()
    {
        return view("pages.coming-soon.index");
    }
}