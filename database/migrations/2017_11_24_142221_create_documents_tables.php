<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Type
        Schema::create('documents_dict_type', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Category
        Schema::create('documents_dict_category', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Status
        Schema::create('documents_dict_status', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('notes');

            $table->integer('document_type_id')->nullable();
            $table->integer('document_status_id')->nullable();
            $table->integer('document_category_id')->nullable();

            $table->nullableMorphs('owned_by');

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
        Schema::dropIfExists('documents_dict_type');
        Schema::dropIfExists('documents_dict_status');
        Schema::dropIfExists('documents_dict_category');
        Schema::dropIfExists('documents');
    }
}
