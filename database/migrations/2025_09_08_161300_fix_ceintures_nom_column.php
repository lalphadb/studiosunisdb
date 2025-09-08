<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ceintures', function (Blueprint $table) {
            if (! Schema::hasColumn('ceintures', 'nom')) {
                $table->string('nom')->after('name')->nullable();
            }
        });

        // Copier les données de 'name' vers 'nom'
        DB::statement('UPDATE ceintures SET nom = name WHERE nom IS NULL');
    }

    public function down()
    {
        Schema::table('ceintures', function (Blueprint $table) {
            $table->dropColumn('nom');
        });
    }
};
