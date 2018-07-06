<!-- TO DO List -->
<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">
    <div class="box-header">
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
            <li class="@if($task->status_id==1){{ 'done' }}@endif" style="" data-item-id="{{ $task->id }}">
                <!-- drag handle -->
                <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                <!-- checkbox -->
                <input type="checkbox" value="" @if($task->status_id==1){{ 'checked' }}@endif  data-id="{{ $task->id }}">
                <!-- todo text -->
                <span class="text nametask" data-name="{{ $task->name }}">{{ \App\Libraries\sl_Text::sommario($task->name,30 ) }}</span>
                @foreach($task->categories as $category)
                <!-- Emphasis label -->
                <small class="label" style="background-color: {{ $category->color }}"><i class="fa fa-clock-o"></i> {{ $category->name }}</small>
                @endforeach
                <!-- General tools such as edit or delete-->
                <div class="tools">
                    <a href="{{ url('/admin/tasks/'.$task->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                    <a href="#" class="delete" data-id="{{ $task->id }}"><i class="fa fa-trash-o"></i></a>
                </div>
            </li>
            @endforeach

        </ul>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix no-border">
        <button type="button" class="btn btn-default pull-right add-task"><i class="fa fa-plus"></i> Add task</button>
    </div>
</div>
<!-- /.box -->
@include('ui.confirmdelete')
@push('scripts')
    <script>
        $(".delete").click(function() {
            $('#confirmdelete').modal('toggle');
            $('.modal-body p').text("Sei sicuro di voler eliminare l'elemento id "+$(this).data('id'));
            $('#confirmForm').prop('action', '/admin/tasks/' + $(this).data('id'));
        });
        $('.add-task').on('click', function(){
            window.location = '{{ url('/admin/tasks/create') }}';
        });
        $('.nametask').on('mouseover', function(){
            $(this).attr('title', $(this).data('name'));
        });
    </script>
@endpush