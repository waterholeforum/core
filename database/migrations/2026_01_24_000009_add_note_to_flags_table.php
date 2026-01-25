<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::table('flags', function (Blueprint $table) {
            $table
                ->text('note')
                ->nullable()
                ->after('reason');
        });
    }

    public function down()
    {
        Schema::table('flags', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
