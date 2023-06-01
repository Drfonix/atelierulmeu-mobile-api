<?php

use App\Models\Car;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('category')->default(Car::CAR_CATEGORIES[0]);
            $table->string('subcategory')->default(Car::CAR_SUB_CATEGORIES[0]);
            $table->string('registration_type')->default(Car::CAR_REGISTRATION_TYPES[0]);
            $table->string('fuel_type')->default(Car::CAR_FUEL_TYPES[0]);
            $table->string('vin_number')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('manufacture_year')->nullable();
            $table->json('tyre_size')->nullable();

            $table->string('motor_power')->nullable();
            $table->string('cylinder_capacity')->nullable();
            $table->string('number_places')->nullable();
            $table->string('max_per_mass')->nullable();
            $table->string('civ_number')->nullable();

            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}

