<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tax Rate, Health Insurance, Pension, etc.
            $table->decimal('rate', 5, 2); // Percentage or fixed amount
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->enum('deduction_type', ['tax', 'benefits'])->default('tax');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_components');
    }
}
