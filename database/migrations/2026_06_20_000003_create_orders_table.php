<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->index();
            $table->dateTime('date');
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->bigInteger('barcode')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('discount_percent')->nullable();
            $table->string('warehouse_name');
            $table->string('oblast');
            $table->bigInteger('income_id')->nullable();
            $table->string('odid')->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('is_cancel')->default(false);
            $table->dateTime('cancel_dt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
