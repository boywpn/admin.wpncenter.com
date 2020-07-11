<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DocumentAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->integer('document_type_id')->unsigned()->nullable()->change();
            $table->foreign('document_type_id')->references('id')->on('documents_dict_type');

            $table->integer('document_status_id')->unsigned()->nullable()->change();
            $table->foreign('document_status_id')->references('id')->on('documents_dict_status');

            $table->integer('document_category_id')->unsigned()->nullable()->change();
            $table->foreign('document_category_id')->references('id')->on('documents_dict_category');
        });

        Schema::table('document_lead', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->change();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

            $table->integer('lead_id')->unsigned()->change();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });

        Schema::table('document_service_contract', function (Blueprint $table) {
            $table->integer('service_contract_id')->unsigned()->change();
            $table->foreign('service_contract_id')->references('id')->on('service_contracts')->onDelete('cascade');

            $table->integer('document_id')->unsigned()->change();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
