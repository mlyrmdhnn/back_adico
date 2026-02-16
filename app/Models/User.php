<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Customer;
use App\Models\HariKerja;
use App\Models\OmsetSalesman;
use App\Models\Requests;
use App\Models\SalesTargets;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'role',
        'phone',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function requests() :HasMany
    {
        return $this->hasMany(Requests::class);
    }

    public function statusRequest() :HasMany
    {
        return $this->hasMany(Requests::class, 'manager_id');
    }

    public function hariKerja() :HasMany
    {
        return $this->hasMany(HariKerja::class, 'salesman_id');
    }

    public function omset() :HasMany
    {
        return $this->hasMany(OmsetSalesman::class, 'salesman_id');
    }

    public function salesTarget() :HasMany
    {
        return $this->hasMany(SalesTargets::class, 'salesman_id');
    }

    public function store() :HasMany
    {
        return $this->hasMany(Store::class, 'salesman_id');
    }

    public function customer() :HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
