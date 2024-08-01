<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermitApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permit_application', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->integer('status')->default(0)->comment('0 = Pending, 1 = Approved || 0=new, 1=pendign, 2=approved, 3=rejected')->nullable();
            $table->integer('permit_charge_id')->nullable();
            $table->integer('holder_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_address')->nullable();
            $table->text('purpose')->nullable();
            $table->integer('applicant_category_id')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->text('surat_permohonan')->nullable();
            $table->text('surat_indemnity')->nullable();
            $table->text('salinan_kad_pengenalan')->nullable();
            $table->text('salinan_lesen_memandu')->nullable();
            $table->text('salinan_geran_kenderaan')->nullable();
            $table->text('salinan_insurans_kenderaan')->nullable();
            $table->text('salinan_road_tax')->nullable();
            $table->text('gambar_kenderaan')->nullable();
            $table->text('surat_sokongan')->nullable();
            $table->integer('phc_check')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('phc_check_date')->nullable();
            $table->integer('phc_check_id')->nullable();
            $table->integer('phc_approve')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('phc_approve_date')->nullable();
            $table->integer('phc_approve_id')->nullable();
            $table->integer('phc_second_approve')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('phc_second_approve_date')->nullable();
            $table->integer('phc_second_approve_id')->nullable();
            $table->integer('jkr_check')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('jkr_check_date')->nullable();
            $table->integer('jkr_check_id')->nullable();
            $table->integer('jkr_approve')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('jkr_approve_date')->nullable();
            $table->integer('jkr_approve_id')->nullable();
            $table->integer('finance_check')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('finance_check_date')->nullable();
            $table->integer('finance_check_id')->nullable();
            $table->integer('finance_approve')->comment('0 = Pending 1 = Approved')->nullable();
            $table->timestamp('finance_approve_date')->nullable();
            $table->integer('finance_approve_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->integer('transaction_status')->comment('0=unpaid, 1=pending, 2=paid')->nullable();
            $table->integer('permit_renewal_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('permit_application');
    }
}
