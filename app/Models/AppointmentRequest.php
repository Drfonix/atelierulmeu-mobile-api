<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object",schema="AppointmentRequest",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="user_id",type="integer",example=2),
 * @OA\Property(property="title",type="string",example="For tyre change"),
 * @OA\Property(property="status",type="string",example="new"),
 * @OA\Property(property="car_plate_number",type="string",example="BV 01 ABC"),
 * @OA\Property(property="client_name",type="string",example="Jhon Doe"),
 * @OA\Property(property="car_make_model",type="string",example="BMW 320D E46"),
 * @OA\Property(property="phone",type="string",example="0740123456"),
 * @OA\Property(property="from",type="string",example="2023-07-25 10:00"),
 * @OA\Property(property="to",type="string",example="2023-07-25 18:00"),
 * @OA\Property(property="duration",type="double",example="6.5"),
 * @OA\Property(property="requested_services",type="array",example={"Tyre change", "Oil change"}, @OA\Items()),
 * @OA\Property(property="meta_data",type="object",example={"serviceStatus": "accepted"}),
 * @OA\Property(property="service_data",type="object",example={"user": {"name": "Jhon"}, "service": {"name": "Demo Tyre Service"}}),
 * )
 * Class AppointmentRequest
 * @package App\Models
 */
class AppointmentRequest extends Model
{
    use HasFactory;

    public const STATUS = ["new", "accepted", "declined"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title','status','car_plate_number', 'client_name', 'car_make_model', 'phone',
        'from', 'to', 'duration', 'requested_services', 'meta_data', 'service_data'
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'from', 'to'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'from' => 'datetime',
        'to' => 'datetime',
        'duration' => 'double',
        'requested_services' => 'array',
        'meta_data' => 'json',
        'service_data' => 'json'
    ];

    /**
     * Get the Account for the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
