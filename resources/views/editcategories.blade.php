@extends('layouts.admin')
@section('headscripts')
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/font-awesome/css/font-awesome.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/Ionicons/css/ionicons.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('theme/plugins/iCheck/all.css') }}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{{ asset('theme/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('theme/bower_components/select2/dist/css/select2.min.css') }}">
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
        Edit Categories
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Categories</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- SELECT2 EXAMPLE -->
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Categories</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        
                              @if (count($errors) > 0)
         <div class = "alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
      @endif
        <!-- /.box-header -->
        <form enctype="multipart/form-data" role="form" action="{{route('saveorupdatecategories')}}" method="post">
        <input type="hidden" value="{{csrf_token()}}" name="_token" />
        <input type="hidden" value="{{$id}}" name="id" name="id" />
            <div class="box-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" placeholder="Category Name" id="category_name" name="category_name" value="{{$categories->category_name}}">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category ID</label>
                            <input type="text" class="form-control" placeholder="Category ID" id="category_name" name="category_id" value="{{$categories->category_id}}">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Profiles</label>
                            <select class="form-control select2" multiple="multiple" data-placeholder="Select a Profile" style="width: 100%;" id="profile[]" name="profile[]">
                                @foreach ($profiles as $profile)
                                @if(is_array($edit_profiles) && in_array($profile->id, $edit_profiles))
                                <option value="{{$profile->id}}" selected="selected">{{ $profile->investigations }}</option>
                                @else
                                <option value="{{$profile->id}}">{{ $profile->investigations }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Service</label>
                            <select class="form-control select2" multiple="multiple" data-placeholder="Select a Service"
                                    style="width: 100%;" id="service[]" name="service[]">
                                @foreach ($services as $service)
                                @if(is_array($edit_services) && in_array($service->id, $edit_services))
                                <option value="{{$service->id}}" selected="selected">{{ $service->investigations }}</option>
                                @else
                                <option value="{{$service->id}}">{{ $service->investigations }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-block">Update Category</button>
                </div>

            </div>
            <!-- /.box-body -->
        </form>
    </div>

</section>
<!-- /.content -->
@endsection
@section('scripts')
<!-- /.content-wrapper -->
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('theme/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('theme/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('theme/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- InputMask -->
<script src="{{ asset('theme/plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{ asset('theme/plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{ asset('theme/plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('theme/bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{ asset('theme/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('theme/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('theme/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{ asset('theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ asset('theme/plugins/iCheck/icheck.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('theme/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('theme/dist/js/demo.js')}}"></script>
<!-- Page script -->
<script>
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'})
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'})
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
    )

    //Date picker
    $('#datepicker').datepicker({
        autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
        showInputs: false
    })
})
</script>
</body>
</html>
