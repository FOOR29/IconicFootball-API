<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();

            // identidad
            $table->string('known_as');
            $table->string('full_name');
            $table->string('img')->nullable();

            // prime data
            $table->string('prime_season');
            $table->string('prime_position');
            $table->enum('preferred_foot', ['left', 'right', 'both']);

            // Stadistics
            $table->unsignedTinyInteger('spd');  // speed
            $table->unsignedTinyInteger('sho');  // shooting
            $table->unsignedTinyInteger('pas');  // passing
            $table->unsignedTinyInteger('dri');  // dribbling
            $table->unsignedTinyInteger('def');  // defending
            $table->unsignedTinyInteger('phy');  // physical

            // rating
            $table->unsignedTinyInteger('prime_rating');

            // relaciones
            $table->foreignId('club_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
