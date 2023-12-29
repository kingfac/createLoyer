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
        Schema::create('loyers', function (Blueprint $table) {
            $table->id();
            $table->string('mois');
            $table->string('annee');
            $table->string('observation')->nullable();
            $table->double('montant');
            $table->unsignedBigInteger('locataire_id');
            //$table->foreign('locataire_id')->references('id')->on('locataires');
            $table->boolean('garantie')->default(false); //cad garantie is not used
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyers');
    }
};
