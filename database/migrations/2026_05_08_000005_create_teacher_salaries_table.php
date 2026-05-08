<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('school_sessions')->onDelete('cascade');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('tax_deduction', 10, 2)->default(0); // Auto-calculated
            $table->decimal('benefits_deduction', 10, 2)->default(0); // Health insurance, etc.
            $table->decimal('net_salary', 10, 2); // base - tax - benefits
            $table->date('salary_date');
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['teacher_id', 'session_id', 'salary_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_salaries');
    }
}
