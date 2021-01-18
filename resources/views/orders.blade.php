@extends('layouts.admin')
@section('headscripts')
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('theme/dist/css/AdminLTE.min.css') }}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{ asset('theme/dist/css/skins/_all-skins.min.css') }}">

<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
@endsection
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Orders
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Orders</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <tr>
                <td>Start Date:</td>
                <td><input type="text" id="min" name="min"  autocomplete="off"></td>
            </tr>
            <tr>
                <td>End Date:</td>
                <td><input type="text" id="max" name="max"  autocomplete="off"></td>
            </tr>
            <!-- /.box -->

            <div class="box">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Order Price</th>
                                <th>Promotion Applied</th>
                                <th>Schedule Date</th>
                                <th>Schedule Time</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Order Report</th>
                                <th>Order Packages</th>
                                <th>Order Profiles</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user_id }}</td>
                                <td>{{ $order->order_id }}</td>
                                <td>{{ $order->order_price }}</td>
                                <td>{{ $order->promotion_price_applied }}</td>
                                <td>{{ $order->schedule_date }}</td>
                                <td>{{ $order->schedule_time }}</td>
                                <td>{{ $order->order_status_name }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td><a href="{{ $order->order_file }}" class="btn btn-info btn-xs" role="button"><span class="glyphicon glyphicon-eye-open"></span></a><span> </span></td>
                                <td>@if(isset($order->order_packages))
                                    @foreach ($order->order_packages as $packs){{ $packs }}@endforeach @endif</td>
                                <td>@if(isset($order->order_profiles))@foreach ($order->order_profiles as $profs) {{ $profs }} @endforeach @endif</td>
                                <td>{{ $order->created_at }}</td>
                                <td><a href="/vieworders/{{$order->id}}" class="btn btn-info btn-xs" role="button" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a><span> </span></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Order Price</th>
                                <th>Promotion Applied</th>
                                <th>Schedule Date</th>
                                <th>Schedule Time</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Order Report</th>
                                <th>Order Packages</th>
                                <th>Order Profiles</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
@endsection
@section('scripts')
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('theme/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('theme/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- DataTables -->
<script src="{{ asset('theme/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{ asset('theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('theme/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('theme/dist/js/demo.js')}}"></script>
<!-- page script -->
<!-- bootstrap datepicker -->
<script src="{{ asset('theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

<script src="{{ asset('https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js')}}"></script>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js')}}"></script>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js')}}"></script>
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js')}}"></script>
<script src="{{ asset('https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js')}}"></script>
<script>
$(function () {
    //Date picker
    $('#min').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    })

    $('#max').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    })

    $('#example1').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        order: [[0, "desc"]],
        "columnDefs": [
            {
                "targets": [10],
                "visible": false
            },
            {
                "targets": [11],
                "visible": false
            }
        ]
    })
})

/* Custom filtering function which will search data in column four between two values */
/*$.fn.dataTable.ext.search.push(
 function (settings, data, dataIndex) {
 var min = parseInt($('#min').val(), 10);
 var max = parseInt($('#max').val(), 10);
 var age = parseFloat(data[10]) || 0; // use data for the age column
 
 if ((isNaN(min) && isNaN(max)) ||
 (isNaN(min) && age <= max) ||
 (min <= age && isNaN(max)) ||
 (min <= age && age <= max))
 {
 return true;
 }
 return false;
 }
 );*/
$(document).ready(function () {
    $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = $('#min').datepicker("getDate");
                var max = $('#max').datepicker("getDate");
                var startDate = new Date(data[12]);
                if (min == null && max == null) {
                    return true;
                }
                if (min == null && startDate <= max) {
                    return true;
                }
                if (max == null && startDate >= min) {
                    return true;
                }
                if (startDate <= max && startDate >= min) {
                    return true;
                }
                return false;
            }
    );


    $("#min").datepicker({onSelect: function () {
            table.draw();
        }, changeMonth: true, changeYear: true});
    $("#max").datepicker({onSelect: function () {
            table.draw();
        }, changeMonth: true, changeYear: true});
    var table = $('#example1').DataTable();

// Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').change(function () {
        table.draw();
    });
});


/*$(document).ready(function () {
 var table = $('#example1').DataTable();
 
 // Event listener to the two range filtering inputs to redraw on input
 $('#min, #max').change(function () {
 table.draw();
 });
 });*/
</script>
@endsection