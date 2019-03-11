{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('task','/admin/tasks/'.$task->id)->add('Aggiorna commento')
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
                    <li class="active"><a href="#editpost" data-toggle="tab" aria-expanded="true">Commento</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="editpost">

                        {!! Form::model($comment, ['url' => url('/admin/tasks/comments',$comment->id),'class' => 'form-horizontal']) !!}
                            <input type="hidden" value="{{ $task->id }}" name="task_id">

                            {!! Form::slText('name','Titolo',null,['placeholder'=> "Titolo non obbligatorio"]) !!}
                            {!! Form::slCkeditor('content','Contenuto') !!}
                            {!! Form::slSelect('approved','Stato',['Non Approvato','Approvato'],[],1) !!}
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
