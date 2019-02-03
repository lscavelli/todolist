<?php

namespace Lfgscavelli\Todolist\Handlers;

class LfmConfigHandler
{

    /**
     * set in config/lfm.php 'shared_folder_name' => Lfgscavelli\Todolist\Handlers\LfmConfigHandler::class
     * add in function getRootFolder() of the vendor/../lfm.php
     * $folder = $this->config->get('lfm.shared_folder_name');
     * if (class_exists($folder)) {
     *      $folder = app()->make($folder)->setFolderName();
     * }
     *
     * in view resource <script>
     *      @if(request()->has('task'))
     *          <?php session()->put('task', request('task')); ?>
     *          goTo('/shares/tasks/{{ request('task') }}');
     *      @endif
     *
     * @return string
     */
    public function setFolderName()
    {
        if (request()->has('task')) {
            return 'shares/tasks/'.request()->task;
        }
        return 'shares';

    }
}
