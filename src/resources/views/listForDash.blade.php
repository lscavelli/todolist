<!-- TO DO List -->
<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>

        <h3 class="box-title">To Do List</h3>

        <div class="box-tools pull-right">
            {{
                $tasks->appends(array_except(\Request::all(),['_token','page']))->links()
            }}
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
        <ul class="todo-list ui-sortable">

            @foreach($tasks as $task)
            <li class="" style="">
                <!-- drag handle -->
                <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                <!-- checkbox -->
                <input type="checkbox" value="">
                <!-- todo text -->
                <span class="text">{{ $task->name }}</span>
                <!-- Emphasis label -->
                <small class="label label-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                <!-- General tools such as edit or delete-->
                <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                </div>
            </li>
            @endforeach

        </ul>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix no-border">
        <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add Attività</button>
    </div>
</div>
<!-- /.box -->