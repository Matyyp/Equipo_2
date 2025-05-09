<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branch_offices', function (Blueprint $table) {
            $table->id('id_branch');
            $table->string('name_branch_offices');
            $table->string('schedule');
            $table->string('street');
            $table->unsignedBigInteger('id_business');
            $table->unsignedBigInteger('id_location');
            $table->foreign('id_business')->references('id_business')->on('businesses')->onDelete('cascade');
            $table->foreign('id_location')->references('id_location')->on('locations')->onDelete('cascade');
            $table->timestamps();

        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedBigInteger('id_branch_office')->after('id_contract');
            $table->foreign('id_branch_office')
                  ->references('id_branch')
                  ->on('branch_offices')
                  ->onDelete('cascade');
        });

        Schema::table('contact_information', function (Blueprint $table) {
            $table->unsignedBigInteger('id_branch_office')->after('id_contact_information');
            $table->foreign('id_branch_office')
                  ->references('id_branch')
                  ->on('branch_offices')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_offices');

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['id_branch_office']);
            $table->dropColumn('id_branch_office');
        });
        Schema::table('contact_information', function (Blueprint $table) {
            $table->dropForeign(['id_branch_office']);
            $table->dropColumn('id_branch_office');
        });
    }
};
