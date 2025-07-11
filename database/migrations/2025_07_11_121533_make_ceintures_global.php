<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->unsignedBigInteger('ecole_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->unsignedBigInteger('ecole_id')->nullable(false)->change();
        });
    }
};
