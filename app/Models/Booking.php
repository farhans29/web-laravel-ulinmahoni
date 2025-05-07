<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 't_transactions';
    protected $primaryKey = 'idrec';
    
    protected $fillable = [
        'order_id',
        'user_id',
        'user_name',
        'user_phone_number',
        'property_name',
        'transaction_date',
        'check_in',
        'check_out',
        'room_name',
        'user_email',
        'booking_days',
        'daily_price',
        'room_price',
        'admin_fees',
        'grandtotal_price',
        'property_type',
        'transaction_type',
        'transaction_code',
        'transaction_status',
        'status',
        'paid_at'
    ];

    protected $dates = [
        'transaction_date',
        'check_in',
        'check_out',
        'paid_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'grandtotal_price' => 'decimal:2',
        'daily_price' => 'decimal:2',
        'room_price' => 'decimal:2',
        'admin_fees' => 'decimal:2',
        'booking_days' => 'integer'
    ];

    public function getStatusColorAttribute()
    {
        return match($this->transaction_status) {
            'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
            'confirmed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
            'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
        };
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->grandtotal_price, 0, ',', '.');
    }

    public function getFormattedDailyPriceAttribute()
    {
        return 'Rp ' . number_format($this->daily_price, 0, ',', '.');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Use transaction_code as the route key
    public function getRouteKeyName()
    {
        return 'transaction_code';
    }
}
