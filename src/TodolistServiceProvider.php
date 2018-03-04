<?php

namespace Lfgscavelli\Todolist;

use Illuminate\Support\ServiceProvider;

class TodolistServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $todolist = "lfgscavelli/todolist";
		if (! $this->app->routesAreCached()) {
             $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }
        // load viste
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'todolist');
        $this->publishes([
            __DIR__.'/resources/views' => base_path('resources/views/vendor/'.$todolist),
        ]);
        // load migrazioni
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Todolist::class, function() {
            return new Todolist;
        });

        $this->app->alias(Todolist::class, 'todo-list');
    }
}
