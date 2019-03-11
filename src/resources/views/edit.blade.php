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

        <div class="col-md-@if(isset($task->id)){{9}}@else{{12}}@endif">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($task, ['url' => url('admin/tasks',$task->id),'class' => 'form-horizontal']) !!}
                            @if(isset($task->id))@method('PUT')@endif
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slCkeditor('description','Descrizione') !!}
                            {!! Form::slSelect('type','Tipo Task',['public'=>__('Pubblico'),'private'=>__('Privato')]) !!}
                            {!! Form::slSelect('status_id','Stato',$stato,[],1) !!}
                            @if(is_array(config('todolist.priority'))){!! Form::slSelect('priority','Priorità',config('todolist.priority')) !!}@endif
                            {!! Form::slDate('date','Data',$task->date ?: Carbon\Carbon::now()) !!}
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

        @if(isset($task->id))
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
                    @include('todolist::partial.navigation-edit')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        @endif

    </div>
    <!-- /.row -->
</section>
@stop
