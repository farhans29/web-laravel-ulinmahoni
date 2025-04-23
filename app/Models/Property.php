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
    // public function getImageAttribute($value)
    // {
    //     return null; // This will show the 'image' field but with null value
    // }
} 