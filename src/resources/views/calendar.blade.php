<div class="box box-solid bg-green-gradient" style="position: relative; left: 0px; top: 0px;">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="fa fa-calendar"></i>

        <h3 class="box-title">Calendar</h3>
        <!-- tools box -->
        <div class="pull-right box-tools">
            <!-- button with a dropdown -->
            <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bars"></i></button>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="{{ url('/admin/tasks/create') }}">Aggiungi task</a></li>
                    <li><a href="#">Cancella task</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Mostra calendar</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
            </button>
        </div>
        <!-- /. tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <!--The calendar -->
        <div id="calendar" style="width: 100%"></div>
    </div>
    <!-- /.box-body -->
</div>
@push('style')
    <link rel="stylesheet" href="{{ asset("/node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css") }}">
@endpush

@push('scripts')
    <!-- datepicker -->
    <script src="{{ asset("/node_modules/moment/min/moment.min.js") }}"></script>
    <script src="{{ asset("/node_modules/moment/locale/it.js") }}"></script>
    <script src="{{ asset("/node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js") }}"></script>
    <script src=""></script>
    <script>
        // The Calender
        $('#calendar').datepicker(
            {
                format: 'dd/mm/yyyy',
                todayBtn: 'true',
                todayHighlight: 'true',
                locale: 'it'
            }
            ).on('changeDate', function(ev) {
            alert(new Date(ev.date));
        });
    </script>
@endpush
