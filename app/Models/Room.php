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
        if (empty($imageData)) {
            return null;
        }

        // If it's already base64 encoded, return as is
        if (is_string($imageData) && strpos($imageData, 'base64,') !== false) {
            return $imageData;
        }

        // If it's binary data, encode it
        if (!empty($imageData)) {
            // Check if it's a file path or binary data
            if (is_string($imageData) && file_exists($imageData)) {
                $imageData = file_get_contents($imageData);
            }
            return 'data:image/jpeg;base64,' . base64_encode($imageData);
        }

        return null;
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
        return json_decode($value, true) ?? [
            'daily' => false,
            'weekly' => false,
            'monthly' => false
        ];
    }
} 