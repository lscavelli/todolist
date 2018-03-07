@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Task"),'/admin/tasks')->add(__("Aggiorna task"))
        ->setTcrumb($task->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($task, ['url' => 'admin/tasks','class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">{{ __("Nome") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text('name',null,['class' => 'form-control', 'placeholder'=> __("Nome")]) !!}
                                </div>
                            </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">{{ __("Descrizione") }}</label>
                            <div class="col-sm-10">
                                {!! Form::textarea('description',null,['class' => 'form-control', 'placeholder'=> __("Descrizione")]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type" class="col-sm-2 control-label">{{ __("Tipo Task") }}</label>
                            <div class="col-sm-10">
                                {!! Form::select('type', ['public'=>__('Pubblico'),'private'=>__('Privato')] ,\Request::input('type') , ['class' => "form-control input-sm", 'id'=>"type"]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_id" class="col-sm-2 control-label">{{ __("Stato") }}</label>
                            <div class="col-sm-10">
                                {!! Form::select('status_id', config('newportal.status_general') , \Request::input('xpage'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                            </div>
                        </div>
                        @if(is_array(config('todolist.priority')))
                        <div class="form-group">
                            <label for="priority" class="col-sm-2 control-label">{{ __("Priorità") }}</label>
                            <div class="col-sm-10">
                                {!! Form::select('priority', config('todolist.priority') , \Request::input('priority'), ['class' => "form-control input-sm"]) !!}
                            </div>
                        </div>
                        @endif
                        @if(is_array(config('todolist.done')))
                            <div class="form-group">
                                <label for="done" class="col-sm-2 control-label">{{ __("Completamento") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::select('done', config('todolist.done') , \Request::input('done'), ['class' => "form-control input-sm"]) !!}
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="date" class="col-sm-2 control-label">{{ __("Data") }}</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <?php $date = (isset($task->date)?$task->date->format('d/m/Y'): null); ?>
                                    {!! Form::text('date',$date ,['class' => 'form-control pull-right date-picker', 'placeholder'=> __("Data"), 'id'=>'date']) !!}
                                </div>
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">{{ __("Salva") }}</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@stop

@section('style')
    <link rel="stylesheet" href="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/css/custom.datetimepicker.css") }}">
@stop
@section('scripts')
    <script src="{{ asset("/node_modules/moment/min/moment.min.js") }}"></script>
    <script src="{{ asset("/node_modules/moment/locale/it.js") }}"></script>
    <script src="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
    <script>
        //moment.locale('it');
        //Date Time Picker
        if ($('.date-time-picker')[0]) {
            $('.date-time-picker').datetimepicker();
        }
        //Time
        if ($('.time-picker')[0]) {
            $('.time-picker').datetimepicker({
                format: 'LT'
            });
        }
        //Date
        if ($('.date-picker')[0]) {
            $('.date-picker').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        }
    </script>
@stop
