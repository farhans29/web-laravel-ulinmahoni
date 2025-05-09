<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 't_properties';
    protected $primaryKey = 'idrec';
    
    protected $fillable = [
        'tags',
        'name',
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
        'features',
        'attributes',
        'image',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'features' => 'array',
        'attributes' => 'array',
        'price' => 'array',
    ];

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
        // If the value is already base64 encoded, return it as is
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $value)) {
            return $value;
        }
        // If it's a file path or binary data, convert to base64
        if (file_exists($value)) {
            return base64_encode(file_get_contents($value));
        }
        return base64_encode($value);
    }
} 