<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->boolean('answerable')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn('answerable');
        });
    }
};
