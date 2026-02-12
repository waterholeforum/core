<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('post_user', function (Blueprint $table) {
            $table->mediumText('draft_body')->nullable()->after('mentioned_at');
            $table
                ->foreignId('draft_parent_id')
                ->nullable()
                ->after('draft_body')
                ->constrained('comments')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('draft_saved_at')->nullable()->index()->after('draft_parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('post_user', function (Blueprint $table) {
            $table->dropConstrainedForeignId('draft_parent_id');
            $table->dropColumn(['draft_body', 'draft_saved_at']);
        });
    }
};
