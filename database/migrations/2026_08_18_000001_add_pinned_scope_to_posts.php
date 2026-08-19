<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('pinned_scope')->nullable()->after('is_pinned');
        });

        DB::table('posts')->where('is_pinned', true)->update(['pinned_scope' => 'global']);

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_pinned', 'created_at']);
            $table->dropColumn('is_pinned');
            $table->index(['pinned_scope', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('pinned_scope');
        });

        DB::table('posts')->whereNotNull('pinned_scope')->update(['is_pinned' => true]);

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['pinned_scope', 'created_at']);
            $table->dropColumn('pinned_scope');
            $table->index(['is_pinned', 'created_at']);
        });
    }
};
