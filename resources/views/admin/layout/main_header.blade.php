<head>
    <meta charset="utf-8">
    <title>Admin panel iTender | @yield('page_title', '')</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
    <meta content="" name="author">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/back/assets/images/logo.png">
    <link href="{{asset('/assets/back/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/back/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/back/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/back/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/back/plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/back/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/back/plugins/timepicker/bootstrap-material-datetimepicker.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/back/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet">
    <link href="/assets/back/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/back/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/back/assets/css/jquery-ui.min')}}.css" rel="stylesheet">
    <link href="{{asset('/assets/back/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('/assets/back/assets/css/metisMenu.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('/assets/back/assets/css/app.min.css?v=123scadas')}}" rel="stylesheet" type="text/css">
    <link href="/assets/back/plugins/animate/animate.css" rel="stylesheet" type="text/css">
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .dtp-select-month-before,.dtp-select-month-after{
            color:#fff!important;
        }
    </style>
    @yield('header_link', '')
</head>