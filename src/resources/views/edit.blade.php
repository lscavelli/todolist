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

                        {!! Form::model($task, ['url' => url('admin/tasks',$task->id),'class' => 'form-horizontal']) !!}
                            @if(isset($task->id))@method('PUT')@endif
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slTextarea('description','Descrizione') !!}
                            {!! Form::slSelect('type','Tipo Task',['public'=>__('Pubblico'),'private'=>__('Privato')]) !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_general')) !!}
                            @if(is_array(config('todolist.priority'))){!! Form::slSelect('priority','Priorità',config('todolist.priority')) !!}@endif
                            @if(is_array(config('todolist.done'))){!! Form::slSelect('done','Completamento',config('todolist.done')) !!}@endif
                            {!! Form::slDate('date','Data',$task->date) !!}
                            {!! Form::slSubmit('Salva') !!}
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
