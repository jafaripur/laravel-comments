<?php

/**
 * Laravel - Commenting system
 * 
 * @author   Araz Jafaripur <mjafaripur@yahoo.com>
 */

namespace jafaripur\LaravelComments;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * This provider is deferred and should be lazy loaded.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register IoC bindings.
     */
    public function register() {
        // Bind the manager as a singleton on the container.
        $this->app->bindShared('jafaripur\LaravelComments\CommentManager', function($app) {
            /**
             * Construct the actual manager.
             */
            return new CommentManager($app);
        });

        // Provide a shortcut to the CommentStore for injecting into classes.
        $this->app->bind('jafaripur\LaravelComments\CommentStore', function($app) {
            return $app->make('jafaripur\LaravelComments\CommentManager')->driver();
        });

        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'comments');
    }

    /**
     * Boot the package.
     */
    public function boot() {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('comments.php')
                ], 'config');
    }

    /**
     * Which IoC bindings the provider provides.
     *
     * @return array
     */
    public function provides() {
        return [
            'jafaripur\LaravelComments\CommentManager',
            'jafaripur\LaravelComments\CommentStore',
        ];
    }

}
