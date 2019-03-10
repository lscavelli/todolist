@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Lista task"))->render() !!}
@stop

@section('content')
    @include('ui.messages')
    <div class="row">

        <div class="col-md-9">
            <app-component></app-component>
        </div>

        <div class="col-md-3">
            <div class="box box-solid">
                <!-- box-body -->
                <div class="box-body no-padding" style="display: block;">
                    @include('todolist::partial.navigation-menu')
                </div>
            </div>
            <!-- /. box -->
        </div>

    </div>
@stop

@push('style')
@endpush

@push('scripts')
        <!-- js Calendar -->
        <script type="text/javascript" src="{{ asset("/vendor/lfgscavelli/todolist/js/runtime.js") }}"></script>
        <script type="text/javascript" src="{{ asset("/vendor/lfgscavelli/todolist/js/polyfills.js") }}"></script>
        <script type="text/javascript" src="{{ asset("/vendor/lfgscavelli/todolist/js/main.js") }}"></script>
@endpush
