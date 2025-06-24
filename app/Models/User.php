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
        return $this->profile_photo_url;
    }

    /**
     * Update the user's profile photo from base64 string.
     *
     * @param string $base64Image
     * @return $this
     * @throws \Exception
     */
    public function updateProfilePhotoFromBase64($base64Image)
    {
        // Decode the base64 string
        $data = base64_decode($base64Image, true);
        if ($data === false) {
            throw new \Exception('Invalid base64 string');
        }
        
        // Detect the image type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($data);
        $mimeParts = explode('/', $mime);
        $type = strtolower(end($mimeParts));
        
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            throw new \Exception('Invalid image type. Only JPG, PNG, and GIF are allowed.');
        }
        
        // Generate unique filename with proper extension
        $filename = 'profile-photos/' . uniqid() . '.' . $type;
        
        // Delete old profile picture if exists
        if ($this->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->profile_photo_path);
        }
        
        // Store the new image
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $data);
        
        // Update user's profile photo path directly without triggering events
        $this->profile_photo_path = $filename;
        $this->save();
        
        return $this;
    }

    /**
     * Set the user's password.
     *
     * @param  string  $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = ($password);
    }
}
