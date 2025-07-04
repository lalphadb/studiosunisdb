<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            if (!Schema::hasColumn('cours', 'duree_minutes')) {
                $table->integer('duree_minutes')->default(60)->after('niveau');
            }
            if (!Schema::hasColumn('cours', 'instructeur')) {
                $table->string('instructeur')->nullable()->after('duree_minutes');
            }
            if (!Schema::hasColumn('cours', 'capacite_max')) {
                $table->integer('capacite_max')->nullable()->after('instructeur');
            }
            if (!Schema::hasColumn('cours', 'prix')) {
                $table->decimal('prix', 8, 2)->nullable()->after('capacite_max');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            $table->dropColumn(['duree_minutes', 'instructeur', 'capacite_max', 'prix']);
        });
    }
};
