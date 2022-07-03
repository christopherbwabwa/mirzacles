<?php

namespace App\Models;

use App\Events\UserSaved;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prefixname',
        'firstname',
        'middlename',
        'lastname',
        'suffixname',
        'username',
        'email',
        'password',
        'photo',
        'type',

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

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function getPhotoAttribute($value)
    {
        return asset($value);
    }

    public function getMiddleinitialAttribute()
    {
        $initial =  strtoupper(substr($this->middlename, 0, 1)) . '.';

        return $initial;
    }

    public function getFullnameAttribute(): string
    {
        $fullName = "$this->firstname";
        if ($this->middlename) {
            $fullName .= ' '. $this->getMiddleinitialAttribute();
        }
        $fullName .= " $this->lastname";

        return $fullName;
    }

    public function details()
    {
        return $this->hasMany(Detail::class);
    }

    
}
