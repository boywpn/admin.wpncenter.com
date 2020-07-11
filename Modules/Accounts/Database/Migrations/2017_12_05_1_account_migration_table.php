<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();

            $table->nullableMorphs('owned_by');

            $table->string('website')->nullable();

            $table->string('account_number')->nullable();

            $table->string('annual_revenue')->nullable();

            $table->string('employees')->nullable();

            $table->integer('account_type_id')->nullable();

            $table->integer('account_industry_id')->nullable();

            $table->integer('account_rating_id')->nullable();

            $table->string('phone')->nullable();

            $table->string('email')->nullable();

            $table->string('secondary_email')->nullable();

            $table->string('fax')->nullable();

            $table->string('skype_id')->nullable();

            $table->string('street')->nullable();

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('country')->nullable();

            $table->string('zip_code')->nullable();

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
        Schema::dropIfExists('accounts');
    }
}
