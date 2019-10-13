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
            $table->string('heading')->default('');
            $table->string('keywords')->default('');
            $table->string('desrciption')->default('');
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
            $table->dropColumn('desrciption');
        });
    }
}
