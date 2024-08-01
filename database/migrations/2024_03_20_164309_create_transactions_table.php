<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->string('ipay_id')->nullable();
            $table->string('auth_code')->nullable();
            $table->text('error_description')->nullable();
            $table->text('signature')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('currency')->nullable();
            $table->text('product_description')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->integer('permit_application_id')->nullable();
            $table->integer('renewal_id')->nullable();
            $table->integer('lost_permit_id')->nullable();
            $table->integer('permit_holder_id')->nullable();
            $table->float('total')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->integer('payment_type')->nullable();
            $table->integer('status')->comment('0=not complete, 1=complete')->nullable();
            $table->text('remark')->nullable();
            $table->string('ccname')->nullable();
            $table->string('s_bankname')->nullable();
            $table->string('s_country')->nullable();
            $table->string('bank_mid')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
