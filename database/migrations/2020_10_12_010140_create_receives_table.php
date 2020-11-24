<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receives', function (Blueprint $table) {
            $table->id();
            $table->string('custom');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->float('old_amount');
            $table->float('pay_amount');
            $table->bigInteger('receive_by')->unsigned()->index();
            $table->dateTime('receive_date');
            $table->text('details')->nullable();
            $table->foreign('receive_by')->on('users')->references('id')->cascadeOnDelete();
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
        Schema::dropIfExists('receives');
    }
}
