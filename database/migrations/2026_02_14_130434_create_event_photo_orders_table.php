<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_photo_orders', function (Blueprint $table) {
            $table->id();
            $table->string('guardian_name', 120);
            $table->string('email', 190);
            $table->string('phone', 30);

            $table->string('rider_full_name', 120);
            $table->string('rider_nickname', 60);
            $table->string('category', 80);

            $table->string('plate_no', 30)->nullable();
            $table->string('batch', 30)->nullable();
            $table->string('instagram', 80)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_photo_orders');
    }
};
