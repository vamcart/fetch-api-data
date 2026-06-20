<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('g_number')->index();
            $table->date('date');
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->bigInteger('barcode')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->string('promo_code_discount')->nullable();
            $table->string('warehouse_name');
            $table->string('country_name');
            $table->string('oblast_okrug_name');
            $table->string('region_name');
            $table->bigInteger('income_id')->nullable();
            $table->string('sale_id');
            $table->string('odid')->nullable();
            $table->string('spp')->nullable();
            $table->decimal('for_pay', 15, 2)->nullable();
            $table->decimal('finished_price', 15, 2)->nullable();
            $table->decimal('price_with_disc', 15, 2)->nullable();
            $table->bigInteger('nm_id')->index();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->boolean('is_storno')->nullable();
            $table->timestamps();
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
}
