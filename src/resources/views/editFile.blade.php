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
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!!
                            $listFiles->columns(['id'=>'Id','thumb'=>__('Anteprima'),'name'=>'Titolo','status_id'=>__('Stato'),'created_at'=>__('Creato il')])
                            ->addSplitButtons([
                                'file'=>'Nuovo file',
                                'image'=>'Nuova immagine',
                            ],false)
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


@push('scripts')
    <script>
        $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
            if (results==null){
                return null;
            }
            else{
                return results[1] || 0;
            }
        }
        $('.splitButtons li a').on('click', function(e) {
            e.preventDefault();
            var height = 720;
            var width = 1080;
            var top =  (screen.height/2)-(height/2) - 100;
            var left = (screen.width/2)-(width/2);
            {{ session()->forget('task') }}
            var win = window.open('/lfm?type='+$(this).attr('href')+'&task='+'{{ $task->id }}', '', 'width='+width+',height='+height+',top='+top+',left='+left);

            var timer = setInterval(function() {
                if(win.closed) {
                    clearInterval(timer);
                    //alert(window.location.href+'?tab_files=1');
                    var str = window.location.href;
                    var url = str.replace("?tab_files=1", '');
                    location.href = url+"?tab_files=1";
                }
            }, 500);
        });

        if ($.urlParam('tab_files')==1) {
            $('.nav-tabs a[href="#tab_files"]').trigger('click');
        }
    </script>
@endpush
