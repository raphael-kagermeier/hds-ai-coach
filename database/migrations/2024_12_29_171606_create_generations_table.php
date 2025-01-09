<?php

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('generations', function (Blueprint $table) {
            $table->id();
            $table->longText('input_text')->nullable();
            $table->foreignIdFor(Lesson::class);
            $table->foreignIdFor(User::class);
            $table->string('status');
            $table->jsonb('images')->nullable();
            $table->longText('generated_text')->nullable();
            $table->longText('final_text')->comment('The final text if the user edits the generated text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generations');
    }
};
