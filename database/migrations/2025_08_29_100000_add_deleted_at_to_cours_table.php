<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('cours') && !Schema::hasColumn('cours', 'deleted_at')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('cours', 'deleted_at')) {
            Schema::table('cours', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
