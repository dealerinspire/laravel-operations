<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExampleOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('example_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('hooked_into_queue')->default(false);
            $table->timestamp('should_run_at');
            $table->timestamp('started_run_at')->nullable();
            $table->timestamp('finished_run_at')->nullable();
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
        Schema::dropIfExists('example_operations');
    }
}
