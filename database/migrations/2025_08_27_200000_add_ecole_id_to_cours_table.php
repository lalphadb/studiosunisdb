<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cours')) {
            return;
        }
        Schema::table('cours', function (Blueprint $table) {
            if (! Schema::hasColumn('cours', 'ecole_id')) {
                if (Schema::hasTable('ecoles')) {
                    $table->foreignId('ecole_id')->nullable()->constrained()->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('ecole_id')->nullable();
                }
                $table->index(['ecole_id', 'actif']);
            }
        });
        if (Schema::hasTable('ecoles') && Schema::hasColumn('cours', 'ecole_id')) {
            $premiereEcole = DB::table('ecoles')->first();
            if ($premiereEcole) {
                DB::table('cours')->update(['ecole_id' => $premiereEcole->id]);
            }
            // Attempt to make non-null if supported
            try {
                Schema::table('cours', function (Blueprint $table) {
                    if (Schema::hasTable('ecoles')) {
                        $table->unsignedBigInteger('ecole_id')->nullable(false)->change();
                    }
                });
            } catch (Throwable $e) { /* ignore for sqlite */
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('cours') || ! Schema::hasColumn('cours', 'ecole_id')) {
            return;
        }
        Schema::table('cours', function (Blueprint $table) {
            try {
                $table->dropForeign(['ecole_id']);
            } catch (Throwable $e) {
            }
            $table->dropColumn('ecole_id');
        });
    }
};
