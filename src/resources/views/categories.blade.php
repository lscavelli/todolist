<!-- TO DO List -->
<div class="box box-success" style="position: relative; left: 0px; top: 0px;">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">List Categories tasks</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- /.box-body -->
        <div class="box-footer text-black" style="">

                @foreach($vocabularies as $vocabulary)
                <div class="row">
                    @foreach($vocabulary->categories as $category)
                        @if($loop->first)<div class="col-sm-6">@endif
                                <div class="clearfix">
                                    <span class="pull-left">{{ $category->name . " #".$category->id }}</span>
                            <small class="pull-right">
                                <?php
                                    try{
                                            $val = (100/count($category->tasks->toArray()))*count($category->tasksClosed->toArray());
                                    } catch(Exception $e){
                                            $val = 0;
                                    }
                                    echo $val."%";
                                ?>
                            </small>
                        </div>
                        <div class="progress xs">
                            <div class="progress-bar" style="width: {{ $val }}%; background-color: {{ $category->color or '#00a65a' }};"></div>
                        </div>
                        @if($loop->index+1 == floor($loop->count/2))</div><div class="col-sm-6">@endif
                        @if($loop->last)</div>@endif
                    @endforeach
                </div>
                <!-- /.row -->
                @endforeach

        </div>
    </div>

</div>
<!-- /.box -->



