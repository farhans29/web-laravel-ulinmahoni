<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBanner extends Model
{
    protected $table = 'm_promo_banners';
    protected $primaryKey = 'idrec';

    protected $fillable = [
        'title',
        'image_id',
        'descriptions',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function images()
    {
        return $this->hasMany(PromoBannerImage::class, 'promo_banner_id', 'idrec');
    }

    public function primaryImage()
    {
        return $this->belongsTo(PromoBannerImage::class, 'image_id', 'idrec');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
