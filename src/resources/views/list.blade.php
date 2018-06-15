@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Lista task"))->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>__("Nome"),'stato','created_at'=>__("Registrata il")])
                    ->actions([__('Profilo'),'assignGroups'=>__('Assegna ai gruppi'),'assignUsers'=>__('Assegna agli utenti')])
                    ->customizes('stato',function($row){
                        return config('todolist.stato')[$row['status_id']];
                    })
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
@stop