<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('puzzle_pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('daily_task_item_id')->constrained()->onDelete('cascade');;
            $table->integer('piece_number');
            $table->boolean('is_opened')->default(false);
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzle_pieces');
    }
};
