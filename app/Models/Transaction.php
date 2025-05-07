<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
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

    protected $casts = [
        'transaction_date' => 'datetime',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with User model if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
