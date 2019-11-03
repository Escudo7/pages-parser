<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDomainTableAddTegsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->text('heading')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('heading');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('keywords');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}
