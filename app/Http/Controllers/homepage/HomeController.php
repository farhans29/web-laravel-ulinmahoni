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
use App\Http\Controllers\promo\PromoController;

class HomeController extends Controller {
    protected $promoController;

    public function __construct(PromoController $promoController)
    {
        $this->promoController = $promoController;
    }

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

        // Get promos data from DB
        $promos = \App\Models\Promo::where('status', 1)
            ->orderByDesc('idrec')
            ->get()
            ->map(function ($promo) {
                return [
                    'id' => $promo->idrec,
                    'title' => $promo->title,
                    'image' => $promo->image,
                    'badge' => 'Promo',
                    'description' => $promo->descriptions,
                ];
            });

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
                // $tag = $property->tags; // Direct string comparison
                
                // if (array_key_exists($tag, $propertyTypes)) {
                //     $propertyTypes[$tag][] = $this->formatProperty($property);
                // }
                $normalizedTag = ucfirst(strtolower(trim($property->tags)));

                if (array_key_exists($normalizedTag, $propertyTypes)) {
                    $propertyTypes[$normalizedTag][] = $this->formatProperty($property);
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
                'heroMedia' => $heroMedia,
                'promos' => $promos
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
                'heroMedia' => $heroMedia,
                'promos' => $promos
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
        // Get the lowest room price if available
        $roomPrice = $property->rooms()
            ->whereNotNull('price_original_monthly')
            ->where('price_original_monthly', '>', 0)
            ->min('price_original_monthly');
        
        // Get price data (already cast to array by the model)
        $price = is_array($property->price) ? $property->price : [];
        
        // Get features data (already cast to array by the model)
        $features = is_array($property->features) ? $property->features : [];

        // Get all property images using the accessor
        $images = $property->images;

        // Use the first image from the images array as the main image, fallback to property image
        $mainImage = !empty($images) ? $images[0]['image'] : $property->image;
        
        return [
            'id' => $property->idrec,
            'name' => $property->name,
            'type' => $property->tags,
            'location' => $property->address,
            'subLocation' => $property->subdistrict . ', ' . $property->city,
            'distance' => $property->distance ? "{$property->distance} km dari {$property->location}" : null,
            'price_original_daily' => $property->price_original_daily,
            'price_original_monthly' => $property->price_original_monthly,
            'price_discounted_daily' => $property->price_discounted_daily,
            'price_discounted_monthly' => $property->price_discounted_monthly,
            'room_price_original_monthly' => $roomPrice ?? $property->price_original_monthly,
            'price' => [
                'original' => $price['original'] ?? 0,
                'discounted' => $price['discounted'] ?? 0
            ],
            'features' => $features,
            'image' => $mainImage,  // Use the first image as main image
            'images' => $images,    // Keep all images array for gallery
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