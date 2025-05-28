<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cost_basic_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_office_id');
            $table->string('name');
            $table->integer('value');
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('branch_office_id')
                  ->references('id_branch')
                  ->on('branch_offices')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cost_basic_services');
    }
};