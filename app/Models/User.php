<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'nickname',
        'github_id',
        'auth_type',
        'provider',
        'provider_id',
        'provider_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected function name(): Attribute {
        return Attribute::make(
            get:fn($value) => Str::upper($value)
        );
        
    }

    // protected function password(): Attribute {
    //     return Attribute::make(
    //         set:fn($value) => Str::bcrypt($value)
    //     );
        
    // }

    protected function isAdmin(): Attribute
    {
        $admins = ['rebin@gmail.com'];
        return Attribute::make(
            get: fn () => in_array($this->email, $admins)
        );
    }

    public function tickets() : HasMany {
        return $this -> hasMany (Ticket::class);
        
    }

    public static function generateUserName($username) {
        if($username === null){
            $username =Str::lower(Str::random(length:8));
        }
        if(User::where('username',$username)->exists()){
            $newUsername = $username.Str::lower(Str::random(length:3));
            $username = self::generateUserName($newUsername);

        }
        return $username;
        
    }


}

