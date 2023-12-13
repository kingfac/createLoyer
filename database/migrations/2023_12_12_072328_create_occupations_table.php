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
        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('ref');
            $table->double('montant');
            $table->boolean('multiple')->default(false);//si l'espace ou le bien peut etre accordé à plusieurs locataire tout en payant le meme prix, exemple table, chaque personne peut avoir sa table et payer le meme prix dans cet espace 
            $table->boolean('actif')->default(true);//dans le cas ou le montant du loyer a changée on va dupliquer l'occupation tout en desactivant l'ancienne et changeant le montant de la nouvelle. réaffecter la nouvelle occupation au locataire pour prendre en charge le nouveau loyer
            $table->unsignedBigInteger('galerie_id');
            $table->unsignedBigInteger('type_occu_id');
            //$table->foreign('galerie_id')->references('id')->on('galeries');
            //$table->foreign('type_occu_id')->references('id')->on('type_occus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occupations');
    }
};
