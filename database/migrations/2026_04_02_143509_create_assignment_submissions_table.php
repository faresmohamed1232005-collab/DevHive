<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','reviewed','accepted','rejected'])->default('pending');
            $table->integer('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->unique(['user_id','assignment_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('assignment_submissions'); }
};