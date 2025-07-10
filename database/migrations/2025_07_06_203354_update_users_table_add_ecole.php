<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'ecole_id')) {
                $table->unsignedBigInteger('ecole_id')->nullable()->after('email');
                $table->foreign('ecole_id')->references('id')->on('ecoles')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ecole_id')) {
                $table->dropForeign(['ecole_id']);
                $table->dropColumn('ecole_id');
            }
        });
    }
};
