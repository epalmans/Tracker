# Tracker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/palmans/tracker.svg?style=flat-square)](https://packagist.org/packages/palmans/tracker)

Simple site visit/statistics tracker for Laravel.
---

Tracker provides a simple way to track your site visits and their statistics. This is a fork of [Arrtrust/Tracker](https://github.com/Arrtrust/Tracker) - providing the same functionality, but modernized.

## Features
- Compatible with Laravel 8+
- Middleware for automatically recording the site view
- Associate site views to Eloquent models to track their views
- Persists unique views based on URL, method, and IP address
- Helper method, Facade, and trait for easing access to services
- Handy 'Cruncher' for number crunching needs
- Flushing and selecting site views with given time spans
- A [phpunit](https://www.phpunit.de) test suite for easy development

## Installation
Installing Tracker is simple.

1. Pull this package in through [Composer](https://packagist.org/packages/palmans/tracker).

    ```bash
    composer install palmans/tracker
    ```

    ```js
    {
        "require": {
            "palmans/tracker": "^2.0"
        }
    }
    ```

2. You may configure the default behaviour of Tracker by publishing and modifying the configuration file. To do so, use the following command.
    ```bash
    php artisan vendor:publish
    ```
    Than, you will find the configuration file on the `config/tracker.php` path. Information about the options can be found in the comments of this file. All of the options in the config file are optional, and falls back to default if not specified; remove an option if you would like to use the default.
    
    This will also publish the migration file for the default `SiteView` model. Do not forget to migrate your database before using Tracker.

3. You may now access Tracker either by the Facade or the helper function.
    ```php
    tracker()->getCurrent();
    Tracker::saveCurrent();
    
    tracker()->isViewUnique();
    tracker()->isViewValid();
    
    tracker()->addTrackable($post);
    
    Tracker::flushAll();
    Tracker::flushOlderThan(Carbon::now());
    Tracker::flushOlderThenOrBetween(Carbon::now(), Carbon::now()->subYear());
    ```

4. It is important to record views by using the supplied middleware to record correct app runtime and memory information. To do so register the middleware in `app\Http\Kernel`.
    ```php
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'guard' => \App\Http\Middleware\Guard::class,
        'track' => \Palmans\Tracker\TrackerMiddleware::class,
    ];
    ```
    It is better to register this middleware as a routeMiddleware instead of a global middleware and use it in routes or route groups definitions as it may not be necessary to persist all site view. This will persist and attach any Trackable that is added to stack to site views automatically when the request has been handled by Laravel.
    
5. To attach views to any model or class, you should implement the `Palmans\Tracker\TrackableInterface` interface. Tracker provides `Palmans\Tracker\Trackable` trait to be used by Eloquent models.
    ```php
        
        use Illuminate\Database\Eloquent\Model as Eloquent;
        use Palmans\Tracker\Trackable;
        use Palmans\Tracker\TrackableInterface;
        
        class Node extends Eloquent implements TrackableInterface
        {
            use Trackable;

            // ...            
        }
    ```
    
    The `Trackable` trait uses Eloquent's `belongsToMany` relationship which utilizes pivot tables. Here is a sample migration for the pivot table:
    ```php
        <?php
        
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Database\Migrations\Migration;
        
        class CreateNodeSiteViewPivotTable extends Migration
        {       
            /**
             * Run the migrations.
             *
             * @return void
             */
            public function up()
            {
                Schema::create('node_site_view', function (Blueprint $table)
                {
                    $table->unsignedBigInteger('node_id');
                    $table->unsignedBigInteger('site_view_id');
        
                    $table->foreign('node_id')
                        ->references('id')
                        ->on('nodes')
                        ->onDelete('cascade');
        
                    $table->foreign('site_view_id')
                        ->references('id')
                        ->on('site_views')
                        ->onDelete('cascade');
        
                    $table->primary(['node_id', 'site_view_id']);
                });
            }
        
            /**
             * Reverse the migrations.
             *
             * @return void
             */
            public function down()
            {
                Schema::drop('node_site_view');
            }
        }

    ```
    
6. Check the `Palmans\Tracker\Cruncher` class and test for statistics number crunching. It is equipped with a number of methods for different types of statistics (mostly counts) in different time spans.

Please check the tests and source code for further documentation, as the source code of Tracker is well tested and documented.

## License
Tracker is released under [MIT License](https://github.com/epalmans/Tracker/blob/master/LICENSE).
