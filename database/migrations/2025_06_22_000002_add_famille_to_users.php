<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('famille_principale_id')->nullable()->after('ecole_id');
            $table->foreign('famille_principale_id')->references('id')->on('users')->onDelete('set null');
            $table->index('famille_principale_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['famille_principale_id']);
            $table->dropIndex(['famille_principale_id']);
            $table->dropColumn('famille_principale_id');
        });
    }
};
