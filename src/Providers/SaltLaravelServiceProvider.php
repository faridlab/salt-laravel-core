<?php

namespace SaltLaravel\Providers;

use Illuminate\Support\ServiceProvider;

class SaltLaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Config
         *
         * Uncomment this function call to make the config file publishable using the 'config' tag.
         */
        // $this->publishes([
        //     __DIR__.'/../../config/salt-laravel.php' => config_path('salt-laravel.php'),
        // ], 'config');

        /**
         * Routes
         *
         * Uncomment this function call to load the route files.
         * A web.php file has already been generated.
         */
        // $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        /**
         * Translations
         *
         * Uncomment the first function call to load the translations.
         * Uncomment the second function call to load the JSON translations.
         * Uncomment the third function call to make the translations publishable using the 'translations' tag.
         */
        // $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'salt-laravel');
        // $this->loadJsonTranslationsFrom(__DIR__.'/../../resources/lang', 'salt-laravel');
        // $this->publishes([
        //     __DIR__.'/../../resources/lang' => resource_path('lang/vendor/salt-laravel'),
        // ], 'translations');

        /**
         * Views
         *
         * Uncomment the first section to load the views.
         * Uncomment the second section to make the view publishable using the 'view' tags.
         */
        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'salt-laravel');
        // $this->publishes([
        //     __DIR__.'/../../resources/views' => resource_path('views/vendor/salt-laravel'),
        // ], 'views');

        /**
         * Commands
         *
         * Uncomment this section to load the commands.
         * A basic command file has already been generated in 'src\Console\Commands\MyPackageCommand.php'.
         */
        // if ($this->app->runningInConsole()) {
        //     $this->commands([
        //         \SaltLaravel\Console\Commands\SaltLaravelCommand::class,
        //     ]);
        // }

        /**
         * Public assets
         *
         * Uncomment this functin call to make the public assets publishable using the 'public' tag.
         */
        // $this->publishes([
        //     __DIR__.'/../../public' => public_path('vendor/salt-laravel'),
        // ], 'public');

        /**
         * Migrations
         *
         * Uncomment the first function call to load the migrations.
         * Uncomment the second function call to make the migrations publishable using the 'migrations' tags.
         */
        // $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        // $this->publishes([
        //     __DIR__.'/../../database/migrations/' => database_path('migrations')
        // ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Config file
         *
         * Uncomment this function call to load the config file.
         * If the config file is also publishable, it will merge with that file
         */
        // $this->mergeConfigFrom(
        //     __DIR__.'/../../config/salt-laravel.php', 'salt-laravel'
        // );
    }
}
