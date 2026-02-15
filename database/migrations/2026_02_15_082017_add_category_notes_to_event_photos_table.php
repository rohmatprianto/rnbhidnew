<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_photos', function (Blueprint $table) {
            // daftar kategori/class untuk event (dipisah dengan ;)
            $table->text('category_notes')->nullable()->after('price_notes');
        });
    }

    public function down(): void
    {
        Schema::table('event_photos', function (Blueprint $table) {
            $table->dropColumn('category_notes');
        });
    }
};
