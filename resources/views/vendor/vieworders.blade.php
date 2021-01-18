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
        Order ID
        <small>#{{$order_data['order_id']}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('orders') }}">Orders</a></li>
        <li class="active">Order View</li>
    </ol>
</section>
<!-- Main content -->
<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> CallLabs.
                <small class="pull-right">Date: {{$order_data['created_at']}}</small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-3 invoice-col">
            <b>User Details</b>
            <address>
                <strong>Name : </strong> {{$user->name}}<br>
                <strong>Gender : </strong>{{$user->gender}}<br>
                <strong>Date Of Birth : </strong>{{$user->dob}}<br>
                <strong>Blood Group : </strong>{{$user->blood_group}}<br>
                <strong>Phone: </strong>{{$user->mobile}}<br>
                <strong>Email: </strong>{{$user->email}}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
            <address>
                <strong>Order Address</strong><br>
                <b>FLAT NUMBER</b> : {{$address_data['flat_number']}}<br>
                <b>STREET NAME</b> : {{$address_data['street_name']}}<br>
                <b>LOCALITY</b> : {{$address_data['locality']}}<br>
                <b>LAND MARK</b> : {{$address_data['land_mark']}}<br>
                <b>CITY</b> : {{$address_data['city']}}<br>
                <b>STATE</b> : {{$address_data['state']}}<br>
                <b>PIN CODE</b> : {{$address_data['pincode']}}<br>

            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
            <strong>Order Details</strong><br>
            <b>Order ID:</b> {{$order_data['order_id']}}<br>
            <b>Transaction ID:</b> {{$order_data['transaction_id']}}<br>
            <b>Payment ID:</b> {{$order_data['payment_id']}}<br>
            <b>Payment Status:</b> {{$order_data['order_status']}}<br>
            <b>Order Schedule Time:</b> {{$order_data['schedule_time']}}<br>
            <b>Order Schedule Date:</b> {{$order_data['schedule_date']}}<br>


        </div>
        <!-- /.col -->
        <div class="col-sm-3 invoice-col">
            <strong>Patient Details</strong><br>
            <b>PATIENT NAME</b> : {{$patient_data['patient_name']}}<br>
            <b>PATIENT GENDER</b> : {{$patient_data['patient_gender']}}<br>
            <b>PATIENT AGE</b> : {{$patient_data['patient_age']}}<br>

        </div>
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>package ID</th>
                        <th>Package Name</th>
                        <th>Price</th>
                        <th>Discount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($packs as $pack)
                    @if($packs != NULL)
                    <tr>
                        <td>{{$pack['id']}}</td>
                        <td>{{$pack['package_id']}}</td>
                        <td>{{$pack['package_name']}}</td>
                        <td>₹{{$pack['price']}}</td>
                        <td>{{$pack['discount_price']}}</td>
                    </tr>
                    @endif
                    @endforeach

                    @foreach ($profs as $prof)
                    @if($profs != NULL)
                    <tr>
                        <td>{{$prof['id']}}</td>
                        <td></td>
                        <td>{{$prof['investigations']}}</td>
                        <td>₹{{$prof['mrp']}}</td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
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
                        <th>ID</th>
                        <th>File</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order_files as $order_file)
                    @if($order_files != NULL)
                    <tr>
                        <td>{{$order_file['id']}}</td>
                        <td>{{$order_file['file']}}</td>
                        <td>{{$order_file['created_at']}}</td>
                        <td>{{$order_file['description']}}</td>
                        <td><a href="{{url('uploads/'.$order_file['file'])}}" class="btn btn-info btn-xs" role="button" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a><span> </span><a href="#" data-id="{{$order_file['id']}}" id="ajaxSubmit" class="btn btn-info btn-danger btn-xs" role="button"> <span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
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
            <p class="lead">Amount Due {{$order_data['created_at']}}</p>

            <div class="table-responsive">
                <table class="table">
                    @if(isset($profs))
                    <?php $sum3 = 0; $discount = 0;?>
                    @foreach ($profs as $prof)
                    @if($profs != NULL)
                    <?php $sum3 += $prof['mrp']; ?>
                    @endif
                    @endforeach
                    @endif 
                    @if($sum3 > 0)
                    <tr>
                        <th style="width:50%">Discount(15%):</th>   
                        <?php $discount = (15 / 100) * $sum3; ?>
                        <td>₹{{$discount}}</td>
                    </tr>
                    <tr>
                    @endif 

                        <th style="width:50%">Subtotal:</th>
                        <?php $sum = 0; ?>
                        @foreach ($packs as $pack)
                        @if($packs != NULL)
                        <?php $sum += $pack['price']; ?>
                        @endif
                        @endforeach

                        <?php $sum2 = 0; ?>
                        @foreach ($profs as $prof)
                        @if($profs != NULL)
                        <?php $sum2 += $prof['mrp']; ?>
                        @endif
                        @endforeach
                        <td>₹{{$sum + $sum2 - $discount }}</td>
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
                    <td>₹{{$sum + $sum2 }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-xs-1">
            <a href="#" target="_blank" class="btn btn-default printMe"><i class="fa fa-print"></i> Print</a>    
        </div>    
        <div class="col-xs-6">
            <form enctype="multipart/form-data" role="form" action="{{route('updateorders')}}" method="post">
                <input type="hidden" value="{{csrf_token()}}" name="_token" />
                <input type="hidden" value="{{$order_data['order_id']}}" name="order_id" id="order_id" />
                <input type="hidden" value="{{$order_data['user_id']}}" name="user_id" id="user_id" />
                <button type="submit" class="btn btn-primary pull-right">Upload Report</button>
                <input type="file" id="user_report" name="user_report" class="pull-right">
            </form>
        </div>
        <div class="col-xs-5">
            <form enctype="multipart/form-data" role="form" action="{{route('changestatus')}}" method="post">
                <div class="form-group">
                    <select id="ajaxStatus" class="form-control">
                        <option>Update Order Status</option>
                        @foreach ($order_status as $order)
                        @if($order_data['order_status'] == $order['id'])
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
            url: "{{url('/deleteorderfile')}}",
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                id: $("#ajaxSubmit").data("id"),
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
            url: "{{url('/changestatus')}}",
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
});
</script>
@endsection
