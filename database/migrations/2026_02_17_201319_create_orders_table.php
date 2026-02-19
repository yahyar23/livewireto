<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('user_id')->nullable(); // للمستخدمين المسجلين
    $table->string('visitor_name')->nullable();       // للزائرين
    $table->string('visitor_phone')->nullable();      // للزائرين
    $table->text('address');
    $table->decimal('total', 10, 2);
    $table->string('status')->default('pending');
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
        Schema::dropIfExists('orders');
    }
}
