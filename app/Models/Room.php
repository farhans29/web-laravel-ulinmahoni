<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 't_rooms';
    protected $primaryKey = 'idrec';

    protected $fillable = [
        'property_id',
        'property_name',
        'name',
        'slug',
        'descriptions',
        'type',
        'level',
        'facility',
        'attachment',
        'periode',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'facility' => 'json',
        'periode' => 'json'
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