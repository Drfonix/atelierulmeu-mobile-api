<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(type="object",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="phone",type="string",example="0741236587"),
 * @OA\Property(property="uuid",type="string",example="9e4cdbc3-83ee-4f5f-820a-4791a1c804fa"),
 * @OA\Property(property="first_name",type="string",example="George"),
 * @OA\Property(property="last_name",type="string",example="Puscas"),
 * @OA\Property(property="email",type="string",example="email@email.com"),
 * )
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'uuid',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'device_token',
        'meta_data'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'device_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'meta_data' => 'json'
    ];

    /**
     * Get the cars of users
     */
    public function cars()
    {
        return $this->hasMany(Car::class, "user_id", "id")
            ->whereNull('deleted_at')
            ->orderBy('name');
    }

    /**
     * Get the cars of users
     */
    public function appointments()
    {
        return $this->hasMany(AppointmentRequest::class, "user_id", "id")
            ->whereNull('deleted_at')
            ->orderBy('status');
    }

    /**
     * Get the cars of users
     */
    public function images()
    {
        return $this->hasMany(UserImage::class, "user_id", "id")
            ->orderBy('name');
    }

    /**
     * Get the documents of users
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class, "user_id", "id")
            ->orderBy('name');
    }

    /**
     * Get the cars of users
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class, "user_id", "id")
            ->orderBy('title');
    }

    /**
     * Checks the car id
     *
     * @param $carId
     */
    public function checkCarId($carId)
    {
        $car = $this->cars()->where('id', $carId)->first();
        if(!$car) {
            throw new UnauthorizedException("You are not the car owner");
        }
    }
}
