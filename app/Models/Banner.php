<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 't_promo_banner';
    protected $primaryKey = 'idrec';
    
    protected $fillable = [
        'title',
        'attachment',
        'descriptions',
        'created_by',
        'updated_by',
        'status'
    ];
} 