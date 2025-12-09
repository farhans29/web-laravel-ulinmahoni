<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Exception;
use Illuminate\Http\Request;

class AllPropertiesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Property::query()->where('status', 1);

            // Apply filters if provided
            if ($request->has('type') && !empty($request->type)) {
                $query->where('tags', $request->type);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            if ($request->has('province') && !empty($request->province)) {
                $query->where('province', $request->province);
            }

            if ($request->has('city') && !empty($request->city)) {
                $query->where('city', $request->city);
            }

            // Handle period filter (daily or monthly)
            if ($request->has('period') && !empty($request->period)) {
                $period = strtolower($request->period);

                if ($period === 'daily' || $period === '1_day') {
                    // Filter properties that have daily pricing
                    $query->whereNotNull('price_original_daily')
                          ->where('price_original_daily', '>', 0);
                } elseif ($period === 'monthly' || $period === '1_month') {
                    // Filter properties that have monthly pricing
                    $query->whereNotNull('price_original_monthly')
                          ->where('price_original_monthly', '>', 0);
                }
            }

            // Handle price filters based on period
            if ($request->has('period') && !empty($request->period)) {
                $period = strtolower($request->period);

                if ($period === 'daily' || $period === '1_day') {
                    if ($request->has('price_min') && !empty($request->price_min)) {
                        $query->where('price_original_daily', '>=', $request->price_min);
                    }
                    if ($request->has('price_max') && !empty($request->price_max)) {
                        $query->where('price_original_daily', '<=', $request->price_max);
                    }
                } elseif ($period === 'monthly' || $period === '1_month') {
                    if ($request->has('price_min') && !empty($request->price_min)) {
                        $query->where('price_original_monthly', '>=', $request->price_min);
                    }
                    if ($request->has('price_max') && !empty($request->price_max)) {
                        $query->where('price_original_monthly', '<=', $request->price_max);
                    }
                }
            } else {
                // Default to monthly if no period specified
                if ($request->has('price_min') && !empty($request->price_min)) {
                    $query->where('price_original_monthly', '>=', $request->price_min);
                }
                if ($request->has('price_max') && !empty($request->price_max)) {
                    $query->where('price_original_monthly', '<=', $request->price_max);
                }
            }

            // Handle date filters (check_in and check_out)
            // Note: This is a basic implementation. For real availability checking,
            // you'd need to check against bookings table
            if ($request->has('check_in') && !empty($request->check_in)) {
                // Store check_in date for view
                $checkIn = $request->check_in;
            }

            if ($request->has('check_out') && !empty($request->check_out)) {
                // Store check_out date for view
                $checkOut = $request->check_out;
            }

            // Order by created_at (newest first)
            $query->orderBy('created_at', 'desc');

            // Get paginated results
            $properties = $query->paginate(12)->withQueryString();

            // Format each property with thumbnail and room prices
            $properties->getCollection()->transform(function ($property) {
                return $this->formatProperty($property);
            });

            // Pass filter values to view
            $filters = [
                'type' => $request->type ?? '',
                'period' => $request->period ?? '',
                'check_in' => $request->check_in ?? '',
                'check_out' => $request->check_out ?? '',
                'search' => $request->search ?? '',
                'province' => $request->province ?? '',
                'city' => $request->city ?? '',
                'price_min' => $request->price_min ?? '',
                'price_max' => $request->price_max ?? '',
            ];

            return view('pages.properties.index', compact('properties', 'filters'));
        } catch (Exception $e) {
            return back()->with('error', 'Error loading properties: ' . $e->getMessage());
        }
    }

    /**
     * Format a property model instance with thumbnail and room prices.
     *
     * @param Property $property The property model instance to format
     * @return Property The property with additional formatted data
     */
    private function formatProperty($property)
    {
        // Get the lowest room price if available
        $roomPrice = $property->rooms()
            ->whereNotNull('price_original_monthly')
            ->where('price_original_monthly', '>', 0)
            ->min('price_original_monthly');

        // Get all property images using the accessor
        $images = $property->images;

        // Use the first image from the images array as the main image, fallback to property image
        $mainImage = !empty($images) ? $images[0]['image'] : $property->image;

        // Get thumbnail - prioritize images with thumbnail field
        $thumbnail = null;
        if (!empty($images)) {
            // Find first image with thumbnail
            foreach ($images as $image) {
                if (!empty($image['thumbnail'])) {
                    $thumbnail = $image['thumbnail'];
                    break;
                }
            }
            // If no thumbnail found, use the first image
            if (!$thumbnail && !empty($images[0]['image'])) {
                $thumbnail = $images[0]['image'];
            }
        }
        // Fallback to property image if no thumbnail
        if (!$thumbnail) {
            $thumbnail = $property->image;
        }

        // Add formatted data to property
        $property->formatted_main_image = $mainImage;
        $property->formatted_thumbnail = $thumbnail;
        $property->formatted_images = $images;
        $property->formatted_room_price = $roomPrice ?? $property->price_original_monthly;

        return $property;
    }
} 