<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyAddUserLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bap_companies', function (Blueprint $table) {

            $table->integer('user_limit')->nullable();
            $table->integer('storage_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bap_companies', function (Blueprint $table) {

            $table->dropColumn(['user_limit','storage_limit']);
        });
    }
}
