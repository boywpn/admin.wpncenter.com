<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('city')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->text('bill_city')->nullable();
            $table->text('ship_city')->nullable();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->text('bill_city')->nullable();
            $table->text('ship_city')->nullable();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->text('city')->nullable();
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->text('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('city');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bill_city');
            $table->dropColumn('ship_city');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('bill_city');
            $table->dropColumn('ship_city');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('city');
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
}
