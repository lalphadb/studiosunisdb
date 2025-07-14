<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('session_cours', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('presences', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('inscriptions_cours', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('paiements', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('seminaires', function (Blueprint $table) {
            $table->softDeletes();
        });
        
        Schema::table('inscriptions_seminaires', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('session_cours', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('presences', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('inscriptions_cours', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('seminaires', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        
        Schema::table('inscriptions_seminaires', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
