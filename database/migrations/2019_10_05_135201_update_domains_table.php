<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->bigInteger('content_length')->nullable();
            $table->string('status_code')->nullable();
            $table->text('body')->nullable();
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
            $table->dropColumn('content_length');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('status_code');
        });
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('body');
        });
    }
}
