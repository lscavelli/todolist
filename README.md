# Newportal-package Todolist

Usato come punto di partenza per alcuni pacchetti Newportal.

Nel file resources/views/dashboard/dashboard.blade.php inserire quanto segue:

@section('content')
    ...
    @if(app()->isAlias('todo-list'))
        <app-component></app-component>
    @endif
    ...
@endsection

@section('scripts')
    ...
	@if(app()->isAlias('todo-list'))
		<!-- js Calendar -->
		<script type="text/javascript" src="{{ asset("/vendor/lfgscavelli/todolist/js/np-calendar.min.js") }}"></script>
	@endif
	...
@endsection




Test cases
----------

Il pacchetto include alcuni casi di test:

* `TestCase` - Rappresenta la classe principale che estende Orchestra\Testbench\TestCase 
* `TestTodolist` - E' la classe che estende TestCase in cui è presente un semplice test