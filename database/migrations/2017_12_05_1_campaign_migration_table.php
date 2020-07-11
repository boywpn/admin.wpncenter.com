<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampaignMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable();

            $table->nullableMorphs('owned_by');

            $table->string('product')->nullable();

            $table->integer('target_audience')->nullable();

            $table->date('expected_close_date')->nullable();

            $table->string('sponsor')->nullable();

            $table->integer('target_size')->nullable();

            $table->integer('campaign_status_id')->nullable();

            $table->integer('campaign_type_id')->nullable();

            $table->float('budget_cost')->nullable();

            $table->float('actual_budget')->nullable();

            $table->integer('expected_response')->nullable();

            $table->float('expected_revenue')->nullable();

            $table->integer('expected_sales_count')->nullable();

            $table->integer('actual_sales_count')->nullable();

            $table->integer('expected_response_count')->nullable();

            $table->integer('actual_response_count')->nullable();

            $table->float('expected_roi')->nullable();

            $table->float('actual_roi')->nullable();

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
        Schema::dropIfExists('campaigns');
    }
}
