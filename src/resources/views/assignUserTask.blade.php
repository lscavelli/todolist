{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Utenti'),'/admin/users')->add(__('Assegna tasks'))
        ->setTcrumb($task->name)
        ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-5">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('Utenti diponibili') }}</h3>
                    </div>
                    {!!
                        $list->setPagination($usersDis)
                            ->columns(['id','cognome'=>__("Cognome"),'nome'=>__("Nome"),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($task) {
                                return "<a href=\"/admin/tasks/". $task->id."/addUser/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">".__('Assegna')."</a>";
                            })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
            <div class="col-md-7">

                {!!
                    $composer->boxNavigator([
                        'type'=> 'primary',
                        'title'=>$task->id ." - ".$task->name,
                        'listMenu'=>[
                            __('Lista tasks')=>url('/admin/tasks'),
                            'divider'=>"divider",
                            __('Modifica')=>url('/admin/tasks/'.$task->id.'/edit'),
                            __('Assegna ai gruppi')=>url('/admin/tasks/assignGroups',$task->id),
                            __('Profilo')=>url('/admin/tasks',$task->id),
                        ],
                        'urlNavPre'=>url('/admin/tasks/assignUsers',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/tasks/assignUsers',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setPagination($usersAss)
                            ->columns(['id','cognome'=>__("Cognome"),'nome'=>__("Nome"),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($task) {
                                return "<a href=\"/admin/tasks/". $task->id."/removeUser/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">".__('Cancella')."</a>";
                            })->render()
                     !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
    <!-- /.content -->
@stop
@push('scripts')
    <script>
        $("#RTYX_xpage").change(function () {
            $("#RTYX_xpage-form").submit();
        });
        $("#HGYU_xpage").change(function () {
            $("#HGYU_xpage-form").submit();
        });
    </script>
@endpush