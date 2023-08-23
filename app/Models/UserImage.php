<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object",schema="UserImage",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="car_id",type="integer",example="2"),
 * @OA\Property(property="name",type="string",example="myfirstpicture"),
 * @OA\Property(property="visible_name",type="string",example="custom name sdfsdf"),
 * @OA\Property(property="type",type="string",example="jpg"),
 * @OA\Property(property="size",type="integer",example="2048"),
 * @OA\Property(property="h_size",type="string",example="1 MB"),
 * @OA\Property(property="favorite",type="boolean",example="true"),
 * @OA\Property(property="meta_data",type="json",example={}),
 * )
 * Class UserImage
 * @package App\Models
 */
class UserImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'car_id',
        'name',
        'visible_name',
        'type',
        'size',
        'h_size',
        'favorite',
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
        'size' => 'integer',
        'favorite' => 'boolean',
        'meta_data' => 'json'
    ];

    /**
     * User model
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }

    /**
     * Car model
     */
    public function car()
    {
        $this->belongsTo(Car::class);
    }
}
