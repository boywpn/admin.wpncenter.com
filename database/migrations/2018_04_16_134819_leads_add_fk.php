<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LeadsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {

            $table->integer('lead_status_id')->unsigned()->nullable()->change();
            $table->foreign('lead_status_id')->references('id')->on('leads_dict_status');

            $table->integer('lead_source_id')->unsigned()->nullable()->change();
            $table->foreign('lead_source_id')->references('id')->on('leads_dict_source');

            $table->integer('lead_industry_id')->unsigned()->nullable()->change();
            $table->foreign('lead_industry_id')->references('id')->on('leads_dict_industry');

            $table->integer('lead_rating_id')->unsigned()->nullable()->change();
            $table->foreign('lead_rating_id')->references('id')->on('leads_dict_rating');

        });

        Schema::table('lead_product', function (Blueprint $table) {
            $table->integer('lead_id')->unsigned()->change();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');

            $table->integer('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
