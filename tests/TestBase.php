<?php

namespace Arrtrust\Tracker\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase;

class TestBase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->resetDatabase();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function resetDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('first_name', 50);
            $table->string('last_name', 50);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_views', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('http_referer', 2000)->nullable();
            $table->string('url', 2000);

            $table->string('request_method', 16);
            $table->string('request_path');

            $table->string('http_user_agent')->nullable();
            $table->string('http_accept_language', 64)->nullable();
            $table->string('locale', 8)->index();

            $table->bigInteger('request_time');
            $table->integer('app_time');
            $table->bigInteger('memory');

            $table->timestamp('created_at');
        });
    }

    protected function getPackageProviders($app)
    {
        return [\Arrtrust\Tracker\TrackerServiceProvider::class];
    }
}
