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
		if (! $this->app->routesAreCached()) {
             $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'todolist');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
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
