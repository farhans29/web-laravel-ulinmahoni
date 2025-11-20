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
        'distance',
        'price',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'features',
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
                        'thumbnail' => $image->image ?? 0,
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
     *
     * @param  string  $value
     * @return string|null
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            // If the value is already a valid base64 string, decode it first
            if (base64_encode(base64_decode($value, true)) === $value) {
                return $value;
            }


            // If it's a file path, read and encode it
            if (is_string($value) && file_exists($value)) {
                $imageData = file_get_contents($value);
                return base64_encode($imageData);
            }

            // If it's binary data, encode it
            if (is_string($value)) {
                return base64_encode($value);
            }

            return base64_decode($value);
            
        } catch (\Exception $e) {
            // \Log::error('Error processing image attribute: ' . $e->getMessage());
            return null;
        }
    }
} 