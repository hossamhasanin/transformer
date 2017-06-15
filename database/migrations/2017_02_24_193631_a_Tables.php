<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ATables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('a_tables', function (Blueprint $table) {
          $table->increments('id');
          $table->string('table');
          $table->integer('status')->default(1);
          $table->string("slug");
          $table->string("link_name");
          $table->string("module_name");
          //$table->string("labels_name");
          $table->string("field_types");
          //$table->string("relationships")->nullable();
          $table->string("icon");
          $table->integer("editable")->default(1);
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
        Schema::dropIfExists('a_tables');
    }
}
