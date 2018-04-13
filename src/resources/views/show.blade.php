@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add(__('tasks'),'/admin/tasks')
    ->add(__('Profilo'))
    ->setTcrumb($task->name)->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-3">

            {!!
                $composer->boxProfile([
                    'subTitle' =>$task->slug,
                    'listMenu'=>[
                        __('Priorità')=>$task->priority,
                        __('Creato il')=>Carbon\Carbon::parse($task->created_at)->format('d/m/Y'),
                        __('Modificato il')=>Carbon\Carbon::parse($task->updated_at)->format('d/m/Y')
                    ],
                    __('description')=>$task->description,
                    'urlEdit'=>url(Request::getBasePath().'/admin/tasks/'.$task->id.'/edit')
                    ])->render()
             !!}

        </div>
        <!-- /.col -->
        <div class="col-md-9">

            {!!
                $composer->boxNavigator([
                    'color'=> 'green',
                    'title'=>$task->id." - ".$task->name,
                    'listMenu'=>[
                        __('Lista tasks')=>url('/admin/tasks'),
                        'divider'=>"divider",
                        __('Modifica')=>url('/admin/tasks/'.$task->id.'/edit')
                    ],
                    'urlNavPre'=>url('/admin/tasks',$pag['preid']->id),
                    'urlNavNex'=>url('/admin/tasks',$pag['nexid']->id),
                    ])->render()
             !!}

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#groups" data-toggle="tab" aria-expanded="true">{{ __('Assegnato ai gruppi') }} @if(isset($listGroups))<span class="label label-success">{{$listGroups->total()}}</span>@endif</a></li>
                    <li><a href="#users" data-toggle="tab">{{ __('Assegnato agli utenti') }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->total()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="groups">
                        @if(isset($listGroups))
                            {!!
                                $listGroups->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($task) {
                                    if ($task->groups->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/tasks/". $task->id ."/removeGroup/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome'=>_('Nome'),'cognome'=>_('Cognome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->customizes('azioni', function($row) use($task) {
                                    if ($task->users->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/tasks/". $task->id ."/removeUser/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                </div>
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
