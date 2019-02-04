<?php

namespace Lfgscavelli\Todolist\Listeners;

use Lfgscavelli\Todolist\Models\Task;
use App\Models\Content\File;
use UniSharp\LaravelFilemanager\Events\ImageWasUploaded;
use UniSharp\LaravelFilemanager\Events\ImageIsUploading;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\Filesystem;

class FileEventsSubscriber {

    private $rp;
    private $storage;

    public function __construct(Filesystem $fs, RepositoryInterface $rp)
    {
        $this->fs = $fs;
        $this->rp = $rp->setModel(Task::class);
        $this->storage =  Storage::disk(config('lfm.disk'))->getDriver()->getAdapter()->getPathPrefix();
    }

    /**
     * dovrebbe impedire l'upload - da verificare
     * @return bool
     */
    public function onImageIsUploading()
    {
        if (!auth()->guard('web')->check()) {
            return false;
        }
    }

    /**
     * Crea il file nel database dopo che è stato caricato
     * @param ImageWasUploaded $event
     */
    public function onImageWasUploaded(ImageWasUploaded $event)
    {
        if (session()->has('tasks')) {
            $task = $this->rp->where('id',session()->get('tasks'))->first();
            $filePath = str_replace($this->storage, "", $event->path());
            $file = $this->rp->setModel(File::class)
                ->where('path', $this->getDirname($filePath))
                ->where('file_name', $this->getFile($event->path()))
                ->first();
            $this->rp->attach($task->files(),$file->id);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = 'Lfgscavelli\Todolist\Listeners\FileEventsSubscriber';
        $events->listen(ImageWasUploaded::class, "{$class}@onImageWasUploaded");
        $events->listen(ImageIsUploading::class, "{$class}@onImageIsUploading");
    }

    /**
     * restituisce il nome del file
     * @param $filePath
     * @return string
     */
    public function getFile($filePath) {
        return $this->fs->basename($filePath);
    }

    /**
     * restituisce la base della dir
     * @param $filePath
     * @return string
     */
    public function getDirname($filePath) {
        return $this->fs->dirname($filePath);
    }

}
