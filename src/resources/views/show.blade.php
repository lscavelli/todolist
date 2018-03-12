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
                    <li class="active"><a href="#users" data-toggle="tab" aria-expanded="true">{{ __('Lista Commenti') }}</a></li>
                    <li><a href="#groups" data-toggle="tab">{{ __('Tag assegnati') }}</a></li>
                    <li><a href="#permissions" data-toggle="tab">{{ __('Categorie assegnate') }}</a></li>
                </ul>
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop
