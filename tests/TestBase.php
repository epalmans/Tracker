<?php

namespace Arrtrust\Tracker\Tests;

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
        // $app['path.base'] = __DIR__.'/..';

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function resetDatabase()
    {
        $this->artisan('migrate', [
            '--database' => 'testbench'
        ])->run();
    }

    protected function getPackageProviders($app)
    {
        return [\Arrtrust\Tracker\TrackerServiceProvider::class];
    }
}
