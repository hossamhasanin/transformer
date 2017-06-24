<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldRendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_renders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("field_id");
            $table->integer("table_id");
            $table->integer("data_s_table");
            $table->string("data_o_table");            
            $table->longText("html_code");
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
        Schema::dropIfExists('field_renders');
    }
}
