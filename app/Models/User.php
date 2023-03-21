<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(type="object")
 * @OA\Property(property="id",type="integer",example=1)
 * @OA\Property(property="phone",type="string",example="0741236587")
 * @OA\Property(property="uuid",type="string",example="9e4cdbc3-83ee-4f5f-820a-4791a1c804fa")
 * @OA\Property(property="first_name",type="string",example="George")
 * @OA\Property(property="last_name",type="string",example="Puscas")
 * @OA\Property(property="email",type="string",example="email@email.com")
 *
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
