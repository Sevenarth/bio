<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestersFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testers_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->unsigned()->nullable();
            $table->foreign('form_id')->references('id')->on('forms')->nullable()->onDelete('set null');
            $table->integer('tester_id')->unsigned();
            $table->foreign('tester_id')->references('id')->on('testers')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('amazon_profiles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testers_forms');
    }
}
