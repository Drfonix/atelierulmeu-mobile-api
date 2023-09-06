<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object",schema="UserDocument",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="car_id",type="integer",example="2"),
 * @OA\Property(property="name",type="string",example="UCFVYNIJJN_651651616.pdf"),
 * @OA\Property(property="type",type="string",example="Asigurare"),
 * @OA\Property(property="size",type="integer",example="2048"),
 * @OA\Property(property="h_size",type="string",example="1 MB"),
 * @OA\Property(property="meta_data",type="json",example={}),
 * )
 * Class UserDocument
 * @package App\Models
 *
 */
class UserDocument extends Model
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
        'type',
        'size',
        'h_size',
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
        'updated_at',
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
