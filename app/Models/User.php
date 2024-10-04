<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'email',
        'password',
        'device_key'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'profile_photo_url_dashboard'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctor() {
     return $this->hasOne(Doctor::class, 'doc_id');   
    }

    public function user_details() {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class, "user_id");
    }

    public function reviews(){
        return $this->hasMany(Review::class, "user_id");
    }

    public function linkedSocialAccounts()
{
    return $this->hasOne(LinkedSocialAccount::class);
}

    protected function defaultProfilePhotoUrl()
    {
        $firstLetter = strtoupper(substr($this->name, 0, 1)); 
        return 'https://ui-avatars.com/api/?name=' . urlencode($firstLetter) . '&color=00FF00&background=EBF4FF&size=50';
    }

    

    public function getProfilePhotoUrlDashboardAttribute() {
        $firstLetter = strtoupper(substr($this->name, 0, 1)); 
        return 'https://ui-avatars.com/api/?name=' . urlencode($firstLetter) . '&color=00FF00&background=EBF4FF'; // Tamanho maior para o dashboard
    }
}
