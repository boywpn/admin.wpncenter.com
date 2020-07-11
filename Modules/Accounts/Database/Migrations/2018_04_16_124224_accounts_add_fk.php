<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {

            $table->integer('account_type_id')->unsigned()->nullable()->change();
            $table->foreign('account_type_id')->references('id')->on('accounts_dict_type');

            $table->integer('account_industry_id')->unsigned()->nullable()->change();
            $table->foreign('account_industry_id')->references('id')->on('accounts_dict_industry');

            $table->integer('account_rating_id')->unsigned()->nullable()->change();
            $table->foreign('account_rating_id')->references('id')->on('accounts_dict_rating');
        });

        Schema::table('account_campaign', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned()->change();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');

            $table->integer('account_id')->unsigned()->change();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
        Schema::table('account_document', function (Blueprint $table) {
            $table->integer('document_id')->unsigned()->change();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');

            $table->integer('account_id')->unsigned()->change();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
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
