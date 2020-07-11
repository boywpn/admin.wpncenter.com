<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DicstsAddIconsColors extends Migration
{

    private $tables = [
        'contacts_dict_status',
        'contacts_dict_source',
        'campaigns_dict_status',
        'leads_dict_status'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        foreach ($this->tables as $tableName) {

            Schema::table($tableName, function (Blueprint $table) {
                $table->string('icon')->nullable();
                $table->string('color')->nullable();
            });

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $tableName) {

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('icon');
                $table->dropColumn('color');
            });
        }
    }
}
