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
 * @OA\Property(property="alert_date",type="string",example="2023-06-05"),
 * @OA\Property(property="recurrent",type="string",example="no"),
 * @OA\Property(property="status",type="string",example="active"),
 * @OA\Property(property="meta_data",type="object",example={}),
 * @OA\Property(property="price",type="integer", example="10.5"),
 * )
 * Class Alert
 * @package App\Models
 */
class Alert extends Model
{
    use HasFactory;

    protected $table = "alerts";

    public const STATUS_ACTIVE = "active";
    public const STATUS_ARCHIVED = "archived";

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
        'recurrent',
        'status',
        'meta_data',
        'price'
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
        'created_at' => 'datetime',
        'meta_data' => 'json',
        'price' => 'float'
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
