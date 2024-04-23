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
        Schema::create('divers', function (Blueprint $table) {
            $table->id();
            $table->integer('locataire_id')->nullable();
            $table->string('besoin');
            $table->double('qte');
            $table->double('cu');
            $table->boolean('entreprise')->default(false);
            $table->double('total')->virtualAs('(qte*cu)');
            $table->integer('users_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divers');
    }
};
