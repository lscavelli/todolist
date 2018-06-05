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
        $this->app->bind(TodoList::class, function() {
            return new TodoList(app(RepositoryInterface::class));
        });

        $this->app->alias(TodoList::class, 'todo-list');
    }
}
