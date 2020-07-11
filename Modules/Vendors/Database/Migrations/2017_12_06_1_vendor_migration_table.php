<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');

    
            $table->string('name')->nullable();

    
            $table->nullableMorphs('owned_by');

    
            $table->integer('vendor_category_id')->nullable();

    
            $table->integer('supplier_id')->nullable();

        
            $table->string('phone')->nullable();

    
            $table->string('mobile')->nullable();

    
            $table->string('email')->nullable();

    
            $table->string('secondary_email')->nullable();

    
            $table->string('fax')->nullable();

    
            $table->string('skype_id')->nullable();

        
            $table->string('street')->nullable();

    
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
        Schema::dropIfExists('vendors');
    }
}
