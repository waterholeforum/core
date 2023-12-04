<?php

use Waterhole\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mentions', function (Blueprint $table) {
            $table->morphs('content');
            $table
                ->foreignId('user_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->primary(['content_type', 'content_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentions');
    }
};
