<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->cascadeOnDelete();
            $table->string('plan');
            $table->unsignedInteger('montant');
            $table->string('devise')->default('XOF');
            $table->string('transaction_id')->unique();
            $table->string('statut')->default('en_attente');
            $table->string('mode_paiement')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};
