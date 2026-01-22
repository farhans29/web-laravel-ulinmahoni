<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'm_properties';
    protected $primaryKey = 'idrec';
    
    protected $fillable = [
        'tags',
        'name',
        'initial',
        'tags',
        'description',
        'province',
        'city',
        'subdistrict',
        'village',
        'postal_code',
        'address',
        'location',
        'latitude',
        'longitude',
        'distance',
        'price',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'features',
        'general',
        'security',
        'amenities',
        'attributes',
        'image',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        // 'image',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'features' => 'array',
        'attributes' => 'array',
        'price' => 'array',
    ];

    /**
     * Get the rooms for the property.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'property_id', 'idrec');
    }
    /**
     * Get minimum price_original_daily from rooms.
     *
     * @return float|null
     */
    public function getPriceOriginalDailyAttribute()
    {
        $prices = $this->rooms()
            ->whereNotNull('price_original_daily')
            ->pluck('price_original_daily')
            ->filter()
            ->flatten();

        return $prices->isNotEmpty() ? $prices->min() : null;
    }

    /**
     * Get minimum price_original_monthly from rooms.
     *
     * @return float|null
     */
    public function getPriceOriginalMonthlyAttribute()
    {
        $prices = $this->rooms()
            ->whereNotNull('price_original_monthly')
            ->pluck('price_original_monthly')
            ->filter()
            ->flatten();

        return $prices->isNotEmpty() ? $prices->min() : null;
    }

    /**
     * Get minimum price_discounted_daily from rooms.
     *
     * @return float|null
     */
    public function getPriceDiscountedDailyAttribute()
    {
        $prices = $this->rooms()
            ->whereNotNull('price_discounted_daily')
            ->pluck('price_discounted_daily')
            ->filter()
            ->flatten();

        return $prices->isNotEmpty() ? $prices->min() : null;
    }

    /**
     * Get minimum price_discounted_monthly from rooms.
     *
     * @return float|null
     */
    public function getPriceDiscountedMonthlyAttribute()
    {
        $prices = $this->rooms()
            ->whereNotNull('price_discounted_monthly')
            ->pluck('price_discounted_monthly')
            ->filter()
            ->flatten();

        return $prices->isNotEmpty() ? $prices->min() : null;
    }

    /**
     * Get thumbnail image for property where thumbnail = 1.
     *
     * @return array|null
     */
    public function getThumbnailImageAttribute()
    {
        try {
            $thumbnail = \DB::selectOne("
                SELECT
                    idrec,
                    property_id,
                    image,
                    thumbnail,
                    caption
                FROM m_property_images
                WHERE property_id = ? AND thumbnail = 1
                LIMIT 1
            ", [$this->idrec]);

            if (empty($thumbnail)) {
                return null;
            }

            return [
                'id' => $thumbnail->idrec ?? null,
                'property_id' => $thumbnail->property_id ?? $this->idrec,
                'image' => $thumbnail->image ?? null,
                'thumbnail' => $thumbnail->thumbnail ?? null,
                'caption' => $thumbnail->caption ?? ''
            ];

        } catch (\Exception $e) {
            \Log::error('Error fetching property thumbnail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all images for each property using raw query.
     *
     * @return array
     */
    public function getImagesAttribute()
    {
        try {
            $images = \DB::select("
                SELECT 
                    idrec,
                    property_id,
                    image,
                    thumbnail,
                    caption
                FROM m_property_images 
                WHERE property_id = ? 
            ", [$this->idrec]);

            if (empty($images)) {
                return [];
            }

            // Process each image to ensure proper base64 encoding
            return array_filter(array_map(function($image) {
                try {
                    if (!is_object($image) || !isset($image->idrec)) {
                        return null;
                    }

                    return [
                        'id' => $image->idrec ?? null,
                        'property_id' => $image->property_id ?? $this->idrec,
                        // 'image' => $this->getProcessedImage($image->image ?? null),
                        'image' => $image->image ?? null,
                        'thumbnail' => $image->thumbnail ?? null,
                        'caption' => $image->caption ?? ''
                    ];
                } catch (\Exception $e) {
                    // Log error and skip this image
                    \Log::error('Error processing property image: ' . $e->getMessage());
                    return null;
                }
            }, $images));

        } catch (\Exception $e) {
            \Log::error('Error fetching property images: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Process the image data to ensure it's properly base64 encoded.
     *
     * @param mixed $imageData
     * @return string|null
     */
    protected function getProcessedImage($imageData)
    {
        if (!$imageData) {
            return null;
        }

        // If already base64, return as is
        if (base64_encode(base64_decode($imageData, true)) === $imageData) {
            return $imageData;
        }

        // If it's a file path, read and encode it
        if (is_string($imageData) && file_exists($imageData)) {
            $imageData = file_get_contents($imageData);
            return base64_encode($imageData);
        }

        // If it's binary data, encode it
        return base64_encode($imageData);
    }

    /**
     * Get the image attribute.
     * Returns the raw file path for URL-based image display.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        // Simply return the raw value (file path) without base64 encoding
        return $value;
    }

    public function getGeneralAttribute($value)
    {
        // return ($value);
        return $this->processRoomFacilityAttribute($value);
    }
    
    public function getSecurityAttribute($value)
    {
        return $this->processRoomFacilityAttribute($value);
        
    }
    public function getAmenitiesAttribute($value)
    {
        return $this->processRoomFacilityAttribute($value);
    }

    protected function processRoomFacilityAttribute($value)
    {
        // return json_decode($value, true) ?? [];
       try {
            // Get the facility IDs from the facility column (JSON array)
            $generalIds = json_decode($value, true) ?? [];
            // \Log::info('General IDs: ', $generalIds);
            if (empty($generalIds) || !is_array($generalIds)) {
                return [];
            }

            // Convert to integers for safe database query
            $generalIds = array_map('intval', $generalIds);
            // return $generalIds;
            try {
                $placeholders = implode(',', array_fill(0, count($generalIds), '?'));
                
                $records = \DB::select("
                    SELECT idrec, facility
                    FROM m_property_facility
                    WHERE idrec IN ($placeholders)
                ", $generalIds);
                
                $facilities = [];
                foreach ($records as $record) {
                    $facilities[] = $record->facility;
                }
                
                // If we found facilities, return them
                if (!empty($facilities)) {
                    return $facilities;
                }
            } catch (\Exception $e) {
                // Fallback to predefined mapping if general table doesn't exist
            $facilityNames = [
                '1' => '~',
                '2' => '~',
                '3' => '~',
                '4' => '~',
                '5' => '~',
                '6' => 'F',
                '7' => 'G',
                '8' => 'H',
                '9' => 'I',
                '10' => 'J'
            ];
            
            $facilities = [];
            foreach ($generalIds as $id) {
                if (isset($facilityNames[(string)$id])) {
                    $facilities[] = $facilityNames[(string)$id];
                }
            }
            
            return $facilities;
            }

        } catch (\Exception $e) {
            // \Log::error('Error processing general attribute: ' . $e->getMessage() . ' for property_id: ' . $this->idrec);
            return [];
        }
    }

    public function chatConversations()
    {
        return $this->hasMany(ChatConversation::class, 'property_id', 'idrec');
    }
} 