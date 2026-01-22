<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->index();

            $table
                ->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('deleted_reason')->nullable();
        });

        DB::table('comments')->update([
            'deleted_at' => DB::raw('hidden_at'),
            'deleted_by' => DB::raw('hidden_by'),
            'deleted_reason' => DB::raw('hidden_reason'),
        ]);

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('hidden_at');
            $table->dropConstrainedForeignId('hidden_by');
            $table->dropColumn('hidden_reason');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->timestamp('hidden_at')->nullable();

            $table
                ->foreignId('hidden_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('hidden_reason')->nullable();
        });

        DB::table('comments')->update([
            'hidden_at' => DB::raw('deleted_at'),
            'hidden_by' => DB::raw('deleted_by'),
            'hidden_reason' => DB::raw('deleted_reason'),
        ]);

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropConstrainedForeignId('deleted_by');
            $table->dropColumn('deleted_reason');
        });
    }
};
