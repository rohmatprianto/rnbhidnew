<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_photo_orders', function (Blueprint $table) {
            // kalau kolom belum ada
            if (!Schema::hasColumn('event_photo_orders', 'event_photo_id')) {
                $table->foreignId('event_photo_id')
                    ->after('id')
                    ->constrained('event_photos')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_photo_orders', function (Blueprint $table) {
            // drop FK dulu baru drop kolom
            if (Schema::hasColumn('event_photo_orders', 'event_photo_id')) {
                $table->dropForeign(['event_photo_id']);
                $table->dropColumn('event_photo_id');
            }
        });
    }
};
