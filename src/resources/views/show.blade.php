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

            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        <img class="img-circle" src="{{ $task->author->getAvatar() }}" alt="{{ $task->author->name }}">
                        <span class="username"><a href="{{ url('admin/users',$task->author->id) }}">{{ $task->author->name }}</a></span>
                        <span class="description">Postato il - {{ Carbon\Carbon::parse($task->created_at)->format('d/m/Y') }} @if($task->created_at!=$task->updated_at) - Modificato il {{ Carbon\Carbon::parse($task->updated_at)->format('d/m/Y') }}@endif</span>
                    </div>
                    <!-- /.user-block -->
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- post text -->
                    <div class="col-md-9">
                        {{ $task->description }}
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="col-md-12">
                        @foreach($task->categories as $category)
                            <a href="/admin/tasks/list/all?category={{ $category->id }}" style="padding-right: 10px">#{{ $category->name }}</a>
                        @endforeach
                        @foreach($task->tags as $tag)
                            <a href="/admin/tasks/list/all?tag={{ $tag->id }}" style="padding-right: 10px">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
                <!-- /.box-footer -->
            </div>

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#comments" data-toggle="tab">{{ __('Commenti') }} @if(isset($comments))<span class="label label-success">{{$comments->total()}}</span>@endif</a></li>
                    <li><a href="#files" data-toggle="tab">{{ __('Lista dei file') }} @if(isset($listFile))<span class="label label-success">{{$listFile->total()}}</span>@endif</a></li>
                    <li><a href="#groups" data-toggle="tab" aria-expanded="true">{{ __('Assegnato ai gruppi') }} @if(isset($listGroups))<span class="label label-success">{{$listGroups->total()}}</span>@endif</a></li>
                    <li><a href="#users" data-toggle="tab">{{ __('Assegnato agli utenti') }} @if(isset($listUsers))<span class="label label-success">{{$listUsers->total()}}</span>@endif</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="comments">
                        @if(isset($comments))
                            {!!
                                $comments->columns(['id','name'=>__('Nome')])
                                ->setColorButton('default')
                                ->setActionsUrl("admin\\tasks\\comments\\".$task->id)
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="files">
                        @if(isset($listFile))
                            {!!
                                $listFile->columns(['id'=>'Id','thumb'=>__('Anteprima'),'name'=>'Titolo','status_id'=>__('Stato'),'created_at'=>__('Creato il')])
                                ->addSplitButtons([
                                   'file'=>'Nuovo file',
                                   'image'=>'Nuova immagine',
                                ],false)
                                ->setColorButton('default')
                                ->actions(function($row) {
                                   return '
                                   <li><a href="'.url('/admin/files/'.$row['id'].'/edit').'">Edita</a></li>
                                   <li><a href="#" class="delete" data-id="'.$row['id'].'">Delete</a></li>';
                                },false)
                                ->setUrlDelete('/admin/files')
                                ->sortFields(['id','name','file_name'])
                                ->customizes('created_at',function($row){
                                   return $row['created_at']->format('d/m/Y');
                                })
                                ->customizes('status_id',function($row){
                                   return config('newportal.status_general')[$row['status_id']];
                                })
                                ->customizes('name',function($row){
                                   return Html::link(url("/admin/files/view",$row['id']), $row['name'], array('title' => $row['name']), true);
                                })
                                ->customizes('thumb',function($row){
                                   $file = "/".config('lfm.thumb_folder_name')."/".$row['file_name'];
                                   $pathFile = $row->getPath().$file;
                                   if($row->isImage() && file_exists($pathFile)) {
                                       return '<div style="text-align:center"><img src=\''.asset("storage/".$row['path'].$file).'\' alt=\''.$row['name'].'\' style="width: 100%; max-width: 45px; height: auto; border-radius: 50%;"></div>';
                                   } else {
                                       return '<div style="text-align:center"><i class="fa '.$row->getIcon() .' fa-3x"></i></div>';
                                   }
                                })->render()
                           !!}

                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="groups">
                        @if(isset($listGroups))
                            {!!
                                $listGroups->columns(['id','name'=>__('Nome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->setColorButton('default')
                                ->customizes('azioni', function($row) use($task) {
                                    if ($task->groups->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/tasks/". $task->id ."/removeGroup/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="users">
                        @if(isset($listUsers))
                            {!!
                                $listUsers->columns(['id','nome'=>_('Nome'),'cognome'=>_('Cognome'),'azioni'=>__('Azioni')])
                                ->showAll(false)
                                ->setColorButton('default')
                                ->customizes('azioni', function($row) use($task) {
                                    if ($task->users->contains('id',$row['id'])) {
                                        return "<a href=\"/admin/tasks/". $task->id ."/removeUser/".$row['id']."\" class=\"btn btn-success btn-xs pull-right\">".__('Cancella')."</a>";
                                    }
                                    return "<a href=\"#\" class=\"btn btn-success btn-xs pull-right disabled\">".__('Cancella')."</a>";
                                })
                                ->render()
                            !!}
                        @endif
                    </div>

                </div>
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

        <div class="col-md-3">

            <div class="box box-solid collapsed-box">
                <div class="box-header with-border bg-green-gradient" style="border-radius: 3px">
                    <h3 class="box-title">Ticket menu</h3>
                    <div class="box-tools ">
                        <button type="button" class="btn btn-success" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: none;">
                    @include('todolist::partial.navigation-edit')
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->

            <div class="box box-solid">
                <div class="box-header with-border bg-green-gradient" style="border-radius: 3px">
                    <h3 class="box-title">Stato Ticket</h3>
                    <div class="box-tools ">
                        <button type="button" class="btn btn-success" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>Data</th>
                            <th style="width: 40px">Stato</th>
                        </tr>
                        @foreach($task->statuses as $status)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $status->pivot->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-info" style="background-color: {{ $status->color }};">{{ $status->name }}</span>
                            </td>

                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->


        </div>

    </div>
    <!-- /.row -->
</section>
@stop
