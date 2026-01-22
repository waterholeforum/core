<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->boolean('require_approval_posts')->default(false);
            $table->boolean('require_approval_comments')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->dropColumn(['require_approval_posts', 'require_approval_comments']);
        });
    }
};
