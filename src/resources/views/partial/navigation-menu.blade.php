{!!
    $navigation->add([
        'Assegnati a me e aperti'=>['icon'=>'fa-user','url'=>url('/admin/tasks')],
        'Assegnati ad altri e aperti'=>['icon'=>'fa-users','url'=>url('/admin/tasks/list/assign-to-other-open')],
        'Assegnati a me e chiusi'=>['icon'=>'fa-user','url'=>url('/admin/tasks/list/assign-to-me-closed')],
        'Assegnati ad altri e chiusi'=>['icon'=>'fa-users','url'=>url('/admin/tasks/list/assign-to-other-closed')],
        'Task aperti'=>['icon'=>'fa-tags','url'=>url('/admin/tasks/list/open')],
        'Task chiusi'=>['icon'=>'fa-tags','url'=>url('/admin/tasks/list/closed')],
        'Tutti i task'=>['icon'=>'fa-tags','url'=>url('/admin/tasks/list/all')],
    ])->render("ui.navigation_content")
!!}
