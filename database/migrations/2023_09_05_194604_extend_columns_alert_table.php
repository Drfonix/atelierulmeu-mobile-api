<?php

use App\Models\Alert;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendColumnsAlertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->string('recurrent')->default("NO")->after('alert_date');
            $table->string('status')->default(Alert::STATUS_ACTIVE)->after('recurrent');
            $table->date('alert_date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dateTime('expiration_date')->nullable();
            $table->dropColumn('recurrent');
            $table->dropColumn('status');
        });
    }
}
