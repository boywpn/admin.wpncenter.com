<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {

            $table->integer('contact_status_id')->unsigned()->nullable()->change();
            $table->foreign('contact_status_id')->references('id')->on('contacts_dict_status');

            $table->integer('contact_source_id')->unsigned()->nullable()->change();
            $table->foreign('contact_source_id')->references('id')->on('contacts_dict_source');

        });

        Schema::table('contact_deal', function (Blueprint $table) {
            $table->integer('deal_id')->unsigned()->change();
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');

            $table->integer('contact_id')->unsigned()->change();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });

        Schema::table('contact_document', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->change();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

            $table->integer('contact_id')->unsigned()->change();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });

        Schema::table('contact_product', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->integer('contact_id')->unsigned()->change();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
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
