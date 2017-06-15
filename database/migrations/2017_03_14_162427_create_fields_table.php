<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field_name');
            $table->integer('table_id');
            $table->string('field_type');
            // check if the laravel created the defualt value in database
            $table->string("visibility")->defualt("show,add,edit,");
            $table->integer('field_nullable');
            $table->string('default_value')->nullable();
            //$table->string('relation_table')->nullable();
            $table->string("label_name");
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
        Schema::dropIfExists('fields');
    }
}
