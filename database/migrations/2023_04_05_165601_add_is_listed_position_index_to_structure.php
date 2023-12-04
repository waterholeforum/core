<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('structure', function (Blueprint $table) {
            $table->index(['is_listed', 'position']);
        });
    }

    public function down(): void
    {
        Schema::table('structure', function (Blueprint $table) {
            $table->dropIndex(['is_listed', 'position']);
        });
    }
};
