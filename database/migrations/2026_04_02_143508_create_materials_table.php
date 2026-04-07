<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();       // مسار الملف في storage
            $table->string('file_url')->nullable();        // رابط خارجي
            $table->enum('type', ['pdf','code','link','zip','other'])->default('pdf');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('materials'); }
};