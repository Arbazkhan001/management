<?php

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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('units');
            $table->date('date');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('customerName');
            $table->foreign('customerName')->references('customerName')->on('customers');
            $table->foreignId('brand_id')->constrained('brands');
            $table->string('brandName');
            $table->foreign('brandName')->references('brandName')->on('brands');
            $table->timestamps();
        });
    }
    //units date customer_id customerName brand id brandName

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
