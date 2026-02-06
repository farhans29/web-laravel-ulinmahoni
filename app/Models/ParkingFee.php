<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingFee extends Model
{
    use HasFactory;

    protected $table = 'm_parking_fee';

    protected $fillable = [
        // Data
        "idrec",    
        "property_id",
        "parking_type",
        "fee",
        "capacity",
        "status",
        // Logs
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'capacity' => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'idrec');
    }
}
