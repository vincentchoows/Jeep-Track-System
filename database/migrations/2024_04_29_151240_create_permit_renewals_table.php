<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermitRenewalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_renewals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permit_application_id')->nullable();
            $table->integer('status')->comment('0=pending, 1=approved, 2=rejected')->nullable();
            $table->timestamp('renewal_start_date')->nullable();
            $table->timestamp('renewal_end_date')->nullable();
            $table->timestamp('old_end_date')->nullable();
            $table->timestamp('date_approved')->nullable();
            $table->integer('approved_by')->nullable();
            $table->timestamp('date_reject')->nullable();
            $table->integer('rejected_by')->nullable();
            $table->string('transaction_id')->nullable();
            $table->integer('transaction_status')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->integer('is_cancel')->nullable();
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
        Schema::dropIfExists('permit_renewals');
    }
}
