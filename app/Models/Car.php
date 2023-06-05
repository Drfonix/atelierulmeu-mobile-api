<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(type="object",
 * @OA\Property(property="id",type="integer",example=1),
 * @OA\Property(property="name",type="string",example="My Car"),
 * @OA\Property(property="category",type="string",example="Autoturism/Automobil mixt"),
 * @OA\Property(property="subcategory",type="string",example="Autoturism"),
 * @OA\Property(property="registration_type",type="string",example="Inmatriculat"),
 * @OA\Property(property="fuel_type",type="string",example="Motorina"),
 * @OA\Property(property="vin_number",type="string",example="WBAAP71111GL33030"),
 * @OA\Property(property="make",type="string",example="BMW"),
 * @OA\Property(property="model",type="string",example="Seria 3"),
 * @OA\Property(property="manufacture_year",type="string",example="2002"),
 * @OA\Property(property="tyre_size",type="string",example={}),
 * @OA\Property(property="motor_power",type="string",example="110"),
 * @OA\Property(property="cylinder_capacity",type="string",example="1995"),
 * @OA\Property(property="number_places",type="string",example="5"),
 * @OA\Property(property="max_per_mass",type="string",example="1550"),
 * @OA\Property(property="civ_number",type="string",example="K884163"),
 * @OA\Property(property="description",type="string",example="My favorite car"),
 * )
 * Class Car
 * @package App\Models
 */
class Car extends Model
{
    use HasFactory;

    public const CAR_CATEGORIES = [
        "Autoturism/Automobil mixt", "Autorulota", "Autovehicul transport persoane",
        "Autovehicul transport marfa", "Autotractor", "Tractor rutier",
        "Motocicleta/Moped/Atv"
    ];

    public const CAR_SUB_CATEGORIES = [
        "Automobil mixt", "Autoturism", "Autoturism de teren", "SUV"
    ];

    public const CAR_REGISTRATION_TYPES = [
        "Inmatriculat", "Inregistrat", "In vederea inmatricularii", "In vederea inregistrari"
    ];

    public const CAR_FUEL_TYPES = [
        "Benzina", "Motorina","Electric","Benzina si GPL","Benzina si alcool","Hybrid benzina",
        "Hybrid motorina", "Fara", "Altul"
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
    ];

    /**
     * Get the Account for the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
