<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'password',
        'status',
        'is_admin',
        'profile_photo_path'
    ];
    
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'phone_number',
        'profile_photo_path',
        'profile_photo_url'
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'profile_picture'
    ];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // If you still need the full name somewhere in your application, you can keep this method
    // but it won't be included in JSON responses unless explicitly called
    /**
     * Get the user's first name.
     *
     * @return string
     */
    public function getFirstNameAttribute($value)
    {
        return $value ?? '';
    }

    /**
     * Get the user's last name.
     *
     * @return string
     */
    public function getLastNameAttribute($value)
    {
        return $value ?? '';
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
    
    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePictureAttribute()
    {
        if ($this->profile_photo_path) {
            // Use config('app.url') to get the base URL from .env
            $baseUrl = rtrim(config('app.url', 'http://localhost:8000'), '/');
            return $baseUrl . '/storage/' . ltrim($this->profile_photo_path, '/');
        }
        
    }

    /**
     * Set the user's password.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = ($password);
    }
}
