<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('channel_taxonomy', function (Blueprint $table) {
            $table
                ->foreignId('channel_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('taxonomy_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('channel_taxonomy');
    }
};
