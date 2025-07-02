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