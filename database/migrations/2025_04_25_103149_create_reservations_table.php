<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('voyage_id')->constrained()->onDelete('cascade');
            $table->integer('nb_personnes');
            $table->enum('statut', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}