<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomConnectionOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_connection_operations', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('custom_connection_operations');
    }
}
