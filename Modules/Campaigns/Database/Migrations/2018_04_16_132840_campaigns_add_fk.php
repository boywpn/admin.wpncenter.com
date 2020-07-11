<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CampaignsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {

            $table->integer('campaign_status_id')->unsigned()->nullable()->change();
            $table->foreign('campaign_status_id')->references('id')->on('campaigns_dict_status');

            $table->integer('campaign_type_id')->unsigned()->nullable()->change();
            $table->foreign('campaign_type_id')->references('id')->on('campaigns_dict_type');

        });

        Schema::table('campaign_contact', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->change();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->integer('contact_id')->unsigned()->change();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
        });
        Schema::table('campaign_deal', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->change();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->integer('deal_id')->unsigned()->change();
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
        });

        Schema::table('campaign_lead', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->change();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->integer('lead_id')->unsigned()->change();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
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
