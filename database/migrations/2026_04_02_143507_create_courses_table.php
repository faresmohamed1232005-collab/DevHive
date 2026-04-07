<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('level', ['beginner','intermediate','advanced'])->default('beginner');
            $table->string('thumbnail')->nullable();
            $table->string('tech_icon')->nullable();       // مثلا: fab fa-js-square
            $table->string('tech_label')->nullable();      // مثلا: JavaScript
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('courses'); }
};