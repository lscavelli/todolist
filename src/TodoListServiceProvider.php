<?php

namespace Lfgscavelli\Todolist;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RepositoryInterface;

class TodoListServiceProvider extends ServiceProvider
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
            $this->loadRoutesFrom(__DIR__.'/routes/api.php');
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        }

        // load viste
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'todolist');
        $this->publishes([__DIR__.'/resources/views' => base_path('resources/views/vendor/'.$todolist),]);

        // load migrazioni
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([__DIR__ . '/database/migrations' => database_path('migrations')], 'migrations');

        // altro
        $this->publishes([__DIR__ .'/config/todolist.php' => config_path('todolist.php')], 'config');
        $this->publishes([__DIR__.'/../public' => public_path('vendor/newportal'),], 'np_public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TodoList::class, function() {
            return new TodoList(app(RepositoryInterface::class));
        });

        $this->app->alias(TodoList::class, 'todo-list');

        $this->app->extend('menu-services', function($service) {
            return $service+['tasks'=>'/tasks'];
        });
    }
}
