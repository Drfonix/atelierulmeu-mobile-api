<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(type="object",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="name",type="string",example="My Car"),
 * @OA\Property(property="plate_number",type="string",example="CJ 01 ABC"),
 * @OA\Property(property="category",type="string",example="Autoturism/Automobil mixt"),
 * @OA\Property(property="subcategory",type="string",example="Autoturism"),
 * @OA\Property(property="registration_type",type="string",example="Inmatriculat"),
 * @OA\Property(property="fuel_type",type="string",example="Motorina"),
 * @OA\Property(property="vin_number",type="string",example="WBAAP71111GL33030"),
 * @OA\Property(property="make",type="string",example="BMW"),
 * @OA\Property(property="model",type="string",example="Seria 3"),
 * @OA\Property(property="manufacture_year",type="string",example="2002"),
 * @OA\Property(property="tyre_size",type="json",example={}),
 * @OA\Property(property="motor_power",type="string",example="110"),
 * @OA\Property(property="cylinder_capacity",type="string",example="1995"),
 * @OA\Property(property="number_places",type="string",example="5"),
 * @OA\Property(property="max_per_mass",type="string",example="1550"),
 * @OA\Property(property="civ_number",type="string",example="K884163"),
 * @OA\Property(property="description",type="string",example="My favorite car"),
 * @OA\Property(property="favorite",type="boolean",example="true"),
 * )
 * Class Car
 * @package App\Models
 */
class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'plate_number',
        'user_id',
        'category',
        'subcategory',
        'registration_type',
        'fuel_type',
        'vin_number',
        'make',
        'model',
        'manufacture_year',
        'tyre_size',
        'motor_power',
        'cylinder_capacity',
        'number_places',
        'max_per_mass',
        'civ_number',
        'description',
        'favorite'
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
        'tyre_size' => 'json',
        'updated_at' => 'datetime',
        'favorite' => 'boolean',
    ];

    /**
     * Get the Account for the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gets car images
     *
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(UserImage::class, "car_id", "id");
    }

    /**
     * Gets car documents
     *
     * @return HasMany
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class, "car_id", "id");
    }

    /**
     * Gets car alerts
     *
     * @return HasMany
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class, "car_id", "id");
    }
}
