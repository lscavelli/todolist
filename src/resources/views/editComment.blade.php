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

                        {!! Form::model($comment, ['url' => url('/admin/tasks/comments'),'class' => 'form-horizontal']) !!}
                            <input type="hidden" value="{{ $task->id }}" name="id">

                            {!! Form::slText('name','Titolo',null,['placeholder'=> "Titolo non obbligatorio"]) !!}
                            {!! Form::slTextarea('content','Contenuto') !!}
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

@section('scripts')
    {!! Html::script('node_modules/ckeditor/ckeditor.js') !!}

    <script>

        var config = {
            extraPlugins: 'codesnippet',
            codeSnippet_theme: 'sunburst',
            language: '{{ config('app.locale') }}',
            filebrowserImageBrowseUrl: '/lfm?type=Images',
            filebrowserImageUploadUrl: '/lfm/upload?type=Images&_token=',
            filebrowserBrowseUrl: '/lfm?type=Files',
            filebrowserUploadUrl: '/lfm/upload?type=Files&_token=',
            allowedContent: true,
            extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
        };

        // Set your configuration options below.

        // Examples:
        // config.language = 'pl';
        // config.skin = 'jquery-mobile';

        // CKFinder.define( configFinder );

        config['height'] = 400;
        CKEDITOR.replace('content', config);
        CKEDITOR.dtd.$removeEmpty.i = 0;
    </script>
@stop
