<?php

use App\Models\AuthRequest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_requests', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('code');
            $table->boolean('confirmed')->default(false);
            $table->bigInteger('user_id')->nullable();
            $table->string('type')->default(AuthRequest::TYPE_LOGIN);
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
        Schema::dropIfExists('auth_requests');
    }
}
