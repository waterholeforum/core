<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Waterhole\Database\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('flags', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');
            $table->string('reason');
            $table
                ->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->timestamp('resolved_at')->nullable();
            $table
                ->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flags');
    }
};
