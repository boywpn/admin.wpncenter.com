<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceContractMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_contracts', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->date('start_date')->nullable();


            $table->date('due_date')->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('service_contract_priority_id')->nullable();


            $table->integer('service_contract_status_id')->nullable();


            $table->text('notes')->nullable();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_contracts');
    }
}
