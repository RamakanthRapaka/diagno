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
        Tests Add
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Tests Add</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Tests Add</h3>
                </div>
                <!-- /.box-header -->
                
                      @if (count($errors) > 0)
         <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @endif

                
                <div class="box-body">
                    <form enctype="multipart/form-data" role="form" action="{{route('saveservices')}}" method="POST">
                    <input type="hidden" value="{{csrf_token()}}" name="_token" />
                    <input type="hidden" id="id" name="id" value="{{ Request::segment(2) }}"/>
                        <!-- text input -->
                        <div class="form-group">
                            <label>Investigations</label>
                            <textarea id="investigation" name="investigation" class="form-control" rows="3" placeholder="Enter Investigations..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>MRP(INR)</label>
                            <input id="mrp" name="mrp" type="text" class="form-control" placeholder="Enter MRP(INR)..." value="">
                        </div>

                        <!-- textarea -->
                        <div class="form-group">
                            <label>Sample Type & Volume</label>
                            <input id="sample" name="sample" type="text" class="form-control" placeholder="Sample Type & Volume..." value="">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputFile">File</label>
                            <input id="file" name="file" type="file" id="exampleInputFile">

                        </div>

                        <div class="form-group">
                            <label>Reporting</label>
                            <input id="reporting" name="reporting" type="text" class="form-control" placeholder="Reporting (In Days)..." value="">
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>


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
<script>
$(function () {
    $('#example1').DataTable()
})
</script>
@endsection