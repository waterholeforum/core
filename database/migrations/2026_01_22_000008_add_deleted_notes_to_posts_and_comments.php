<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table
                ->text('deleted_message')
                ->nullable()
                ->after('deleted_reason');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table
                ->text('deleted_message')
                ->nullable()
                ->after('deleted_reason');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('deleted_message');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('deleted_message');
        });
    }
};
