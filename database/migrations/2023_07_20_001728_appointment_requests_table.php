<?php

use App\Models\AppointmentRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppointmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->default("");
            $table->string('status')->default(AppointmentRequest::STATUS[0]);
            $table->string('car_plate_number')->nullable();
            $table->string('client_name')->nullable();
            $table->string('car_make_model')->nullable();
            $table->string('phone')->nullable();
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->double('duration',5,2)->default(0.00);
            $table->json('requested_services')->nullable();
            $table->json('meta_data')->nullable();
            $table->json('service_data')->nullable();
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
        Schema::dropIfExists('appointment_requests');
    }
}
