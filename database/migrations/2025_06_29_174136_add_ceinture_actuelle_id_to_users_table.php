<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('ceinture_actuelle_id')->nullable();
            $table->foreign('ceinture_actuelle_id')->references('id')->on('ceintures');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ceinture_actuelle_id']);
            $table->dropColumn('ceinture_actuelle_id');
        });
    }
};
