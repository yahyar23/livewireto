<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
             $table->foreignId('product_id')->constrained()->cascadeOnDelete();

    $table->string('sku')->unique();

    $table->decimal('price', 10, 2);
    $table->decimal('compare_price', 10, 2)->nullable(); // سعر قبل الخصم

    $table->integer('stock')->default(0);

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
        Schema::dropIfExists('product_variants');
    }
}
