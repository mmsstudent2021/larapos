<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId("item_id");
            $table->integer("quantity");
            $table->double("unit_price");
            $table->double("cost");
            $table->date("date");
            $table->foreignId("voucher_id");
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
        Schema::dropIfExists('voucher_lists');
    }
};
