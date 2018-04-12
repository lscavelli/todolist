{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Gruppi'),'/admin/groups')->add(__('Assegna tasks'))
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
                        <h3 class="box-title">{{ __('Gruppi diponibili') }}</h3>
                    </div>
                    {!!
                        $list->setModel($groupsDis)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('RTYX_')
                            ->customizes('azioni', function($row) use($task) {
                                return "<a href=\"/admin/tasks/". $task->id."/addGroup/".$row['id']."\" class=\"btn btn-warning btn-xs pull-right\">".__('Assegna')."</a>";
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
                            __('Assegna agli utenti')=>url('/admin/tasks/assignUsers',$task->id),
                            __('Profilo')=>url('/admin/tasks',$task->id),
                        ],
                        'urlNavPre'=>url('/admin/tasks/assignGroups',$pag['preid']->id),
                        'urlNavNex'=>url('/admin/tasks/assignGroups',$pag['nexid']->id),
                        ])->render()
                 !!}

                <div class="box box-default">
                    {!!
                         $list->setModel($groupsAss)
                            ->columns(['id','name'=>__('Nome'),'azioni'])
                            ->showActions(false)
                            ->showAll(false)
                            ->setPrefix('HGYU_')
                            ->customizes('azioni', function($row) use($task) {
                                return "<a href=\"/admin/tasks/". $task->id."/removeGroup/".$row['id']."\" class=\"btn btn-danger btn-xs pull-right\">".__('Cancella')."</a>";
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