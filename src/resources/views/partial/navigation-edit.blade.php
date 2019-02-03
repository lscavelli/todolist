{!!
    $navigation->add([
        'Edita Task'=>['icon'=>'fa-edit','url'=>url('/admin/tasks/'.$task->id.'/edit')],
        'Categorizzazione'=>['icon'=>'fa-tags','url'=>url('/admin/tasks/categorization',$task->id)],
        'Carica file'=>['icon'=>'fa-upload','url'=>url('/admin/tasks/files',$task->id)],
        'Mostra Profilo Task'=>['icon'=>'fa-tasks','url'=>url('/admin/tasks',$task->id)],
        'Assegna agli Utenti'=>['icon'=>'fa-user','url'=>url('/admin/tasks/assignUsers',$task->id)],
        'Assegna ai Gruppi'=>['icon'=>'fa-group','url'=>url('/admin/tasks/assignGroups',$task->id)],
        'Elimina Task'=>['icon'=>'fa-remove','url'=>'#', 'class'=>'delete'],
    ])->render("ui.navigation_content")
!!}

@include('ui.confirmdelete')
@section('scripts')
    <script>
        $('#confirmdelete').on('shown.bs.modal', function(){});

        $(".delete").click(function() {
            $('#confirmdelete').modal('toggle');
            $('.modal-body p').text("Sei sicuro di voler eliminare l'elemento id "+$(this).data('id'));
            $('#confirmForm').prop('action', '{{ url('/admin/tasks') }}/' + $(this).data('id'));
        });
    </script>
@stop
