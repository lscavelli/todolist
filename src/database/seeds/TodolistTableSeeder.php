<?php


namespace Lfgscavelli\Todolist\Seeds;

use Illuminate\Database\Seeder;
use Lfgscavelli\Todolist\Models\Task;

class TodolistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Task::create([
            'name' => 'Primo task di esempio',
            'description' => 'Questa applicazione consente agli utenti di tenere traccia delle attività. Queste attività includono una descrizione, un livello di priorità (da 1 a 9, dove 1 è la priorità più alta) e un valore booleano che indica se l\'attività è stata completata o meno. Ogni attività può essere modificata o cancellata.',
            'type' => 'public',
            'date' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);
    }
}
