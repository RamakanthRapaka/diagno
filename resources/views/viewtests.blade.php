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
        Invoice
        <small>#{{$order_tests['order_id']}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Invoice</li>
    </ol>
</section>

<!-- Main content -->
<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> CallLabs.
                <small class="pull-right">Date: {{$order_tests['created_at']}}</small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <address>
                <strong>User Info.</strong><br>
                Mobile : {{$order_tests['mobile']}}<br>
            </address>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>File</th>
                        <th>View File</th>
                        <th>Creaed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_tests_files as $user_tests_file)
                    @if($user_tests_files != NULL)
                    <tr>
                        <td>{{$user_tests_file['id']}}</td>
                        <td>{{$user_tests_file['file']}}</td>
                        <td><a href="{{$user_tests_file['file']}}" class="btn btn-info btn-xs" role="button" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a><span> </span></td>
                        <td>{{$user_tests_file['created_at']}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>

    <!-- Table row -->
    <div class="row row no-print">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Test Name</th>
                        <th>Test Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                <form role="form" action="{{route('saveusertestdetails')}}" method="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" value="{{$order_tests['order_id']}}" name="order_id_details" id="order_id_details" />
                    <input type="hidden" value="{{$order_tests['user_id']}}" name="user_id" id="user_id" />
                    <td></td>
                    <td><input id="test_name" name="test_name" type="text" class="form-control" placeholder="Test Name" value=""></td>
                    <td><input id="price" name="price" type="text" class="form-control" placeholder="Test Price" value=""></td>
                    <td><button type="button" class="btn btn-primary add-row">Add Test</button></td>
                </form>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Test Name</th>
                        <th>Test Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    </tr>
                    @foreach ($usertestsdetails as $details)
                    @if($details != NULL)
                    <tr>
                        <td>{{$details['id']}}</td>
                        <td>{{$details['test_name']}}</td>
                        <td>{{$details['price']}}</td>
                        <td><span> </span><a href="#" data-id="{{$details['id']}}" id="ajaxSubmit" class="btn btn-info btn-danger btn-xs" role="button"> <span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                    @endif
                    @endforeach
            </tbody>
            </table>
            <table class="table table-striped tests-rows">            </table>

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
<div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
            <p class="lead">Payment Methods:</p>
            <img src="{{ asset('theme/dist/img/credit/visa.png') }}" alt="Visa">
            <img src="{{ asset('theme/dist/img/credit/mastercard.png') }}" alt="Mastercard">
            <img src="{{ asset('theme/dist/img/credit/american-express.png') }}" alt="American Express">
            <img src="{{ asset('theme/dist/img/credit/paypal2.png') }}" alt="Paypal">

            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
                dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
            <p class="lead">Amount Due {{$order_tests['created_at']}}</p>

            <div class="table-responsive">
                <table class="table">
                    @if(isset($usertestsdetails))
                    <?php $sum3 = 0; $discount = 0;?>
                    @foreach ($usertestsdetails as $tests_details)
                    @if($usertestsdetails != NULL)
                    <?php $sum3 += $tests_details['price']; ?>
                    @endif
                    @endforeach
                    @endif 
                    @if($sum3 > 0)
                    <tr>
                        <th style="width:50%">Discount(15%):</th>   
                        <?php $discount = (15 / 100) * $sum3; ?>
                        <td>₹{{$discount}}</td>
                    </tr>
                    @endif
                    
                    <tr>

                        <th style="width:50%">Order Total:</th>
                        
                        <td>₹{{$sum3}}</td>
                    </tr>
                    <tr>

                        <th style="width:50%">Subtotal:</th>
                        <td>₹{{$sum3 - $discount}}</td>
                    </tr>
                    <!--<tr>
                        <th>Tax (9.3%)</th>
                        <td>₹ 10.34</td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        <td>$5.80</td>
                    </tr>
                    <tr>-->
                    <th>Total:</th>
                    <td>₹{{$sum3 - $discount }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-xs-1">
            <a href="#" target="_blank" class="btn btn-default printMe"><i class="fa fa-print"></i> Print</a>    
        </div>
        <div class="col-xs-6">
            <form enctype="multipart/form-data" role="form" action="{{route('updateusertests')}}" method="post">
                <input type="hidden" value="{{csrf_token()}}" name="_token" />
                <input type="hidden" value="{{$order_tests['order_id']}}" name="order_id" id="order_id" />
                <input type="hidden" value="{{$order_tests['user_id']}}" name="user_id" id="user_id" />
                <button type="submit" class="btn btn-primary pull-right">Upload Report</button>
                <input type="file" id="user_report" name="user_report" class="pull-right">
            </form>
        </div>
        <div class="col-xs-5">
            <form enctype="multipart/form-data" role="form" action="{{route('changeuserteststatus')}}" method="post">
                <div class="form-group">
                    <select id="ajaxStatus" class="form-control">
                        <option>Update Order Status</option>
                        @foreach ($order_status as $order)
                        @if($order_tests['order_status'] == $order['id'])
                        <option value="{{$order['id']}}" selected>{{$order['status_name']}}</option>
                        @else
                        <option value="{{$order['id']}}">{{$order['status_name']}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->
<div class="clearfix"></div>
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
<script>
$('.printMe').click(function () {
    window.print();
});
$(function () {
    $('#example1').DataTable()
})

jQuery(document).ready(function () {
    jQuery('#ajaxSubmit').click(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{url('/deleteusertestdetails')}}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                order_id_details: $("#ajaxSubmit").data("id"),
                order_id:$("#order_id").val()
            },
            success: function (result) {
                console.log(result);
            }});
    });

    jQuery('#ajaxStatus').change(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{url('/changeuserteststatus')}}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: $('#order_id').val(),
                order_status: $("#ajaxStatus option:selected").val(),
            },
            success: function (result) {
                console.log(result);
            }});
    });

    $(".add-row").click(function (e) {
        console.log('AM HERE');
        var test_name = $("#test_name").val();
        var price = $("#price").val();
        var markup = "<tr><td><input type='checkbox' name='record'></td><td>" + test_name + "</td><td>" + price + "</td><td></td></tr>";
        $(".tests-rows").append(markup);

        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{url('/saveusertestdetails')}}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                order_id_details: $('#order_id_details').val(),
                user_id: $('#user_id').val(),
                test_name: $("#test_name").val(),
                price: $("#price").val()
            },
            success: function (result) {
                console.log(result);
            }});
    });

    // Find and remove selected table rows
    $(".delete-row").click(function () {
        $("table tbody").find('input[name="record"]').each(function () {
            if ($(this).is(":checked")) {
                $(this).parents("tr").remove();
            }
        });
    });
});
</script>
@endsection
