<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('inscriptions_seminaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminaire_id')->constrained('seminaires')->onDelete('cascade');
            $table->foreignId('membre_id')->constrained('membres')->onDelete('cascade');
            $table->foreignId('ecole_id')->nullable()->constrained('ecoles')->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit','present','absent','annule'])->default('inscrit');
            $table->decimal('montant_paye', 8, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->text('notes_participant')->nullable();
            $table->timestamp('date_paiement')->nullable();
            $table->boolean('certificat_obtenu')->default(false);
            $table->timestamps();
            $table->unique(['seminaire_id', 'membre_id']);
        });
    }
    public function down() { Schema::dropIfExists('inscriptions_seminaires'); }
};
