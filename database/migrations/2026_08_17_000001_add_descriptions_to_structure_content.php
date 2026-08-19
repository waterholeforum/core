<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('description')->nullable()->after('icon');
        });

        Schema::table('structure_links', function (Blueprint $table) {
            $table->text('description')->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('structure_links', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
