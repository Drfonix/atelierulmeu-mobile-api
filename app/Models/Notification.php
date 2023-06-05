<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="user_id",type="integer",example=1),
 * @OA\Property(property="car_id",type="integer",example=1),
 * @OA\Property(property="type",type="string",example="ITP"),
 * @OA\Property(property="title",type="string",example="Audi ITP"),
 * @OA\Property(property="message",type="string",example="My message"),
 * @OA\Property(property="alert_date",type="string",example="2023-06-05 10:00:00"),
 * @OA\Property(property="expiration_date",type="string",example="2023-06-06 10:00:00"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * )
 * Class Notification
 * @package App\Models
 */
class Notification extends Model
{
    use HasFactory;

    public const NOTIFICATION_TYPES = [
        "ITP", "RCA","Vinieta","Cauciucuri","Custom",
        "Asigurare de calatorie","Buletin","Permis de conducere"
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'car_id',
        'type',
        'title',
        'message',
        'alert_date',
        'expiration_date',
        'meta_data',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'user_id' => 'integer',
        'car_id' => 'integer',
        'updated_at' => 'datetime',
        'alert_date' => 'datetime',
        'expiration_date' => 'datetime',
        'meta_data' => 'json',
    ];

    /**
     * Get the User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Car
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
