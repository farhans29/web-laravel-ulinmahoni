<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'm_rooms';
    protected $primaryKey = 'idrec';

    protected $fillable = [
        'idrec',
        'property_id',
        'property_name',
        'name',
        'slug',
        'descriptions',
        'type',
        'level',
        'facility',
        'image',
        'image2',
        'image3',
        'periode',
        'periode_daily',
        'periode_monthly',
        'status',
        'created_by',
        'updated_by',
        'price',
        'price_original_daily',
        'price_discounted_daily',
        'price_original_monthly',
        'price_discounted_monthly',
        'admin_fees',
    ];

    protected $casts = [
        'facility' => 'json',
        'periode' => 'json',
        'price' => 'json'
    ];


    /**
     * Get the property that owns the room.
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get all images for the room.
     *
     * @return array
     */
    public function getImagesAttribute()
    {
        try {
            $images = \DB::select("
                SELECT 
                    idrec,
                    room_id,
                    image,
                    caption
                FROM m_room_images 
                WHERE room_id = ? 
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
                        'room_id' => $image->room_id ?? $this->idrec,
                        'image' => $this->getProcessedImage($image->image ?? null),
                        'caption' => $image->caption ?? ''
                    ];
                } catch (\Exception $e) {
                    // Log error and skip this image
                    \Log::error('Error processing room image: ' . $e->getMessage());
                    return null;
                }
            }, $images));

        } catch (\Exception $e) {
            \Log::error('Error fetching room images: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Process the image data to ensure it's properly base64 encoded.
     *
     * @param  mixed  $imageData
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
     * Get the facility attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getFacilityAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Get the attachment attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getAttachmentAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Get the periode attribute.
     *
     * @param  string  $value
     * @return array
     */
    public function getPeriodeAttribute($value)
    {
        if (empty($value)) {
            return [
                'daily' => false,
                'weekly' => false,
                'monthly' => false
            ];
        }

        // If it's a JSON string, decode it
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        // If we have an array, use its values
        if (is_array($value)) {
            return [
                'daily' => $value['daily'] ?? false,
                'weekly' => $value['weekly'] ?? false,
                'monthly' => $value['monthly'] ?? false
            ];
        }
        
        return [
            'daily' => false,
            'weekly' => false,
            'monthly' => false
        ];
    }
} 