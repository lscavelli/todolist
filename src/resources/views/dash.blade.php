@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Lista task"))->render() !!}
@stop


@section('content')
    <!-- Main content -->
    <section class="content">
        @include('ui.messages')
        <div class="row">
            <div class="col-md-3">

                {!!
                    $composer->boxProfile([
                        'subTitle' =>'titolo uno',
                        'listMenu'=>[
                            'Creato il'=>'test',
                            'Modificato il'=>'test'
                        ],
                        'description'=>'desk'
                        ])->render()
                 !!}

            </div>
            <!-- /.col -->
            <div class="col-md-9">

                <div class="box box-solid bg-green-gradient" style="margin-bottom: 10px">
                    <div class="box-header ui-sortable-handle">
                        <i class="fa fa-calendar"></i><h3 class="box-title">Dashboard</h3>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-bars"></i></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="http://lara.test/admin/organizations">Lista organizzazioni</a></li>
                                    <li class="divider"></li>
                                    <li><a href="http://lara.test/admin/organizations/1/edit">Modifica</a></li>
                                    <li><a href="http://lara.test/admin/organizations/assignUser/1">Assegna utenti</a></li>
                                    <li><a href="http://lara.test/admin/organizations/assignFilial/1">Assegna filiali</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="location.href='http://lara.test/admin/organizations/1';"><i class="fa fa-chevron-left"></i></button>
                            <button type="button" class="btn btn-success btn-sm" onclick="location.href='http://lara.test/admin/organizations/1';"><i class="fa fa-chevron-right"></i></button>
                        </div>
                        <!-- /. tools -->
                    </div>
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#AssToMeOpen" data-toggle="tab" aria-expanded="true">Assegnati a me e aperti @if(isset($listToMeOpen))<span class="label label-success">{{$listToMeOpen->count()}}</span>@endif</a></li>
                        <li><a href="#AssToOtherOpen" data-toggle="tab" aria-expanded="true">Ass. ad altri e aperti @if(isset($listToOtherOpen))<span class="label label-success">{{$listToOtherOpen->count()}}</span>@endif</a></li>
                        <li><a href="#AssToMeClosed" data-toggle="tab" aria-expanded="true">Assegnati a me e chiusi @if(isset($listToMeClosed))<span class="label label-success">{{$listToMeClosed->count()}}</span>@endif</a></li>
                        <li><a href="#AssToOtherClosed" data-toggle="tab" aria-expanded="true">Ass. ad altri e chiusi @if(isset($listToOtherClosed))<span class="label label-success">{{$listToOtherClosed->count()}}</span>@endif</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- /.tab-pane -->
                        <div class="tab-pane active" id="AssToMeOpen">
                            @if(isset($listToMeOpen))
                                {!!
                                    $listToMeOpen->columns(['id','name'=>__("Nome"),'created_at'=>__("Creato il"),'author'])
                                    ->actions([__('Profilo'),
                                        'assignGroups'=>[__('Assegna ai gruppi'),'tasks-assign'],
                                        'assignUsers'=>[__('Assegna agli utenti'),'tasks-assign'],
                                        'closed'=>'Chiudi immediatamente'])
                                    ->actions(function($row) {
                                        return '<li><a href="'.url('/admin/tasks/'.$row['id'],'/edit#categorization').'>Categorizzazione</a></li>';
                                    })
                                    ->customizes('created_at',function($row){
                                        return $row['created_at']->format('d/m/Y');
                                    })
                                    ->customizes('author',function($row){
                                        return $row->author->name;
                                    })
                                    ->render()
                                !!}
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="AssToOtherOpen">
                            @if(isset($listToOtherOpen))
                                {!!
                                    $listToOtherOpen->columns(['id','name'=>__("Nome"),'created_at'=>__("Creato il"),'author'])
                                    ->showActionsDefault(false)
                                    ->actions([__('Profilo'),'assignGroups'=>[__('Assegna ai gruppi'),'tasks-assign'],'assignUsers'=>[__('Assegna agli utenti'),'tasks-assign']])
                                    ->customizes('created_at',function($row){
                                        return $row['created_at']->format('d/m/Y');
                                    })
                                    ->customizes('author',function($row){
                                        return $row->author->name;
                                    })
                                    ->render()
                                !!}
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="AssToMeClosed">
                            @if(isset($listToMeClosed))
                                {!!
                                    $listToMeClosed->columns(['id','name'=>__("Nome"),'created_at'=>__("Creato il"),'author'])
                                    ->actions([__('Profilo'),'assignGroups'=>[__('Assegna ai gruppi'),'tasks-assign'],'assignUsers'=>[__('Assegna agli utenti'),'tasks-assign'],'open'=>'Riapri task'])
                                    ->customizes('created_at',function($row){
                                        return $row['created_at']->format('d/m/Y');
                                    })
                                    ->customizes('author',function($row){
                                        return $row->author->name;
                                    })
                                    ->render()
                                !!}
                            @endif
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="AssToOtherClosed">
                            @if(isset($listToOtherClosed))
                                {!!
                                    $listToOtherClosed->columns(['id','name'=>__("Nome"),'created_at'=>__("Creato il"),'author'])
                                    ->showActionsDefault(false)
                                    ->actions([__('Profilo'),'assignGroups'=>[__('Assegna ai gruppi'),'tasks-assign'],'assignUsers'=>[__('Assegna agli utenti'),'tasks-assign']])
                                    ->customizes('created_at',function($row){
                                        return $row['created_at']->format('d/m/Y');
                                    })
                                    ->customizes('author',function($row){
                                        return $row->author->name;
                                    })
                                    ->render()
                                !!}
                            @endif
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->

                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-code-fork"></i>
                        <h3 class="box-title">titolo --- </h3>
                    </div>
                    descrizione
                </div> <!-- /.box -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@stop
