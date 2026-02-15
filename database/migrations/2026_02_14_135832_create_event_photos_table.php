<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120);              // Pushbike Race
            $table->string('location', 120)->nullable(); // Stadion
            $table->date('event_date')->nullable();    // 2026-02-12
            $table->string('status', 20)->default('open'); // open/closed/draft
            $table->string('cover_path')->nullable();  // images/... (optional)
            $table->text('price_notes')->nullable();   // “2 Moto ...”
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_photos');
    }
};

