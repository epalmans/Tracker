<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_views', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('request_method', 16);
            $table->string('url', 2000);
            
            $table->string('request_path');
            $table->string('route')->nullable()->index();
            
            $table->string('http_referer', 2000)->nullable();
            $table->string('http_user_agent')->nullable();
            $table->string('http_accept_language', 64)->nullable();
            $table->string('locale', 8)->index();
            $table->string('ip')->nullable()->index();

            $table->timestamp('requested_at');
            $table->integer('app_time');
            $table->bigInteger('memory');

            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('site_views');
    }
}
