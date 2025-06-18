<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {
            // Rendre reference_interne nullable temporairement
            $table->string('reference_interne')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->string('reference_interne')->nullable(false)->change();
        });
    }
};
