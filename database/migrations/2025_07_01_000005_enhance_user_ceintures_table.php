<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            // Ajouter ecole_id pour multi-tenant
            if (!Schema::hasColumn('user_ceintures', 'ecole_id')) {
                $table->foreignId('ecole_id')->after('user_id')->constrained('ecoles')->onDelete('cascade');
            }
            
            // Enrichir avec métadonnées
            if (!Schema::hasColumn('user_ceintures', 'date_attribution')) {
                $table->date('date_attribution')->after('ceinture_id')->default(now());
            }
            
            if (!Schema::hasColumn('user_ceintures', 'attribue_par')) {
                $table->string('attribue_par')->nullable()->after('date_attribution');
            }
            
            if (!Schema::hasColumn('user_ceintures', 'commentaires')) {
                $table->text('commentaires')->nullable()->after('attribue_par');
            }
            
            if (!Schema::hasColumn('user_ceintures', 'certifie')) {
                $table->boolean('certifie')->default(false)->after('commentaires');
            }
            
            // Index
            $table->index(['user_id', 'ecole_id'], 'idx_user_ecole');
            $table->index(['ecole_id', 'date_attribution'], 'idx_ecole_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ceintures', function (Blueprint $table) {
            $columns = ['ecole_id', 'date_attribution', 'attribue_par', 'commentaires', 'certifie'];
            
            if (Schema::hasColumn('user_ceintures', 'ecole_id')) {
                $table->dropForeign(['ecole_id']);
            }
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('user_ceintures', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
