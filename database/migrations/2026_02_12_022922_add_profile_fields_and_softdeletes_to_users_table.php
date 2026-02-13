<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('sosmed', 255)->nullable()->after('phone'); // bisa link IG/WA/LinkedIn, dll

            // enum status: aktif, review, reject
            // default: review (umum untuk user baru, admin review dulu)
            $table->enum('status', ['aktif', 'review', 'reject'])
                ->default('review')
                ->after('sosmed');

            // Soft delete
            $table->softDeletes()->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'sosmed', 'status']);
            $table->dropSoftDeletes();
        });
    }
};
