@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Lista task"))->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">

        <div class="col-md-9">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>__("Nome"),'created_at'=>__("Creato il"),'status_id'=>__('Stato'),'author'])
                    ->setActionsUrl('/admin/tasks')
                    ->setColorButton('default')
                    ->actions([__('Profilo'),
                        'files'=>'Carica file',
                        'assignGroups'=>[__('Assegna ai gruppi'),'tasks-assign'],
                        'assignUsers'=>[__('Assegna agli utenti'),'tasks-assign'],
                        'closed'=>'Chiudi immediatamente',
                        'open'=>'Riapri adesso',
                        'categorization'=>'Categorizzazione',
                        'comments'=>'Commenti'])
                    ->customizes('name',function($row){
                        return link_to(url('admin\tasks',$row->id), $row->name);
                    })
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })
                    ->customizes('status_id',function($row){
                        $color  =   (config('todolist.stato-label')[$row['status_id']]);
                        $tipo   =   (config('todolist.stato')[$row['status_id']]);
                    return "<span class=\"label $color\">".$tipo."</span>";
                })
                    ->customizes('author',function($row){
                        return $row->author->name;
                    })
                    ->render()
                !!}

            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border" style="background-color: #f8f8f8; border-radius: 3px">
                    <h3 class="box-title">Task menu</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: block;">
                    @include('todolist::partial.navigation-menu')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->

            <div class="small-box bg-green">
                <div class="inner">
                    <h3>100%</h3>
                    <p>Task Calendar - Categories</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="/admin/tasks/calendar" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>
@stop

@push('style')
@endpush

@push('scripts')
@endpush
