<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;
use Waterhole\Models\User;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mentions', function (Blueprint $table) {
            $table->string('mentionable_type')->nullable()->after('content_id');
            $table->unsignedBigInteger('mentionable_id')->nullable()->after('mentionable_type');
        });

        DB::table('mentions')->update([
            'mentionable_type' => (new User())->getMorphClass(),
            'mentionable_id' => DB::raw('user_id'),
        ]);

        Schema::table('mentions', function (Blueprint $table) {
            $table->dropPrimary(['content_type', 'content_id', 'user_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('mentions', function (Blueprint $table) {
            $table->unique(
                ['content_type', 'content_id', 'mentionable_type', 'mentionable_id'],
                'mentionable_unique_index',
            );
        });
    }

    public function down(): void
    {
        Schema::table('mentions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('content_id');
        });

        DB::table('mentions')
            ->where('mentionable_type', (new User())->getMorphClass())
            ->update(['user_id' => DB::raw('mentionable_id')]);

        DB::table('mentions')
            ->where('mentionable_type', '!=', (new User())->getMorphClass())
            ->delete();

        Schema::table('mentions', function (Blueprint $table) {
            $table->dropUnique('mentionable_unique_index');
            $table->dropColumn(['mentionable_type', 'mentionable_id']);
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->primary(['content_type', 'content_id', 'user_id']);
        });
    }
};
