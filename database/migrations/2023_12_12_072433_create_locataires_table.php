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
        Schema::create('locataires', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->nullable();
            $table->string('nom');
            $table->string('postnom')->nullable();
            $table->string('prenom');
            $table->string('tel', 14);
            $table->double('garantie');
            $table->double('nbr');
            $table->unsignedBigInteger('mp')->nullable();
            $table->unsignedBigInteger('ap')->nullable();
            $table->unsignedBigInteger('occupation_id');
            $table->string('num_occupation');
            $table->boolean('actif');
            $table->string('noms')->virtualAs('concat(nom, \' \', postnom, \' \', prenom)');
            //$table->foreign('occupation_id')->references('id')->on('occupations')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locataires');
    }
};
