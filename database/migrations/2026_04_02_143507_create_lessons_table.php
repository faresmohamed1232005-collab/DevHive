<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();       // YouTube URL أو مسار الملف
            $table->enum('video_type', ['youtube','upload'])->default('youtube');
            $table->integer('duration')->default(0);       // بالدقائق
            $table->integer('order')->default(0);
            $table->enum('level', ['basic','intermediate','advanced'])->default('basic');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lessons'); }
};