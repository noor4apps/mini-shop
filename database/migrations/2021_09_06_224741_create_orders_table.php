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
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();

            $table->boolean('different_shipping')->default(false);

            $table->string('s_address_line_1')->nullable();
            $table->string('s_address_line_2')->nullable();

            $table->string('payment_method')->nullable();

            $table->float('sub_total')->default(0);
            $table->float('order_total')->default(0);

            $table->string('payment_status')->default('pending');
            $table->string('order_status')->default('pending');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
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
