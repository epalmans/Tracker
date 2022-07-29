<?php

namespace Arrtrust\Tracker;

use Illuminate\Support\ServiceProvider;

class TrackerServiceProvider extends ServiceProvider
{
    const version = '2.0.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['tracker'];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTracker();
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('tracker.php'),
        ]);

        $this->publishes([
            __DIR__.'/../migrations/0000_00_00_000000_create_site_views_table.php' => database_path('/migrations/'.now()->format('Y_m_d_His').'_create_site_views_table.php'),
        ], 'migrations');
    }

    /**
     * Registers the tracker service
     */
    protected function registerTracker()
    {
        $this->app->singleton(
            'tracker',
            'Arrtrust\Tracker\Tracker'
        );
    }
}
