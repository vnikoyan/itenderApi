<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Itender - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description">
        <meta content="" name="author">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/back/assets/images/favicon.ico">
        <!-- App css -->
        <link href="/assets/back/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/back/assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="/assets/back/assets/css/icons.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/back/assets/css/metisMenu.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/back/assets/css/app.min.css" rel="stylesheet" type="text/css">
    </head>
    <body class="bg-card">
        <div class="container-fluid">
            <!-- Log In page -->
            <div class="row vh-100">
                <div class="col-lg-3 pr-0">
                    <div class="auth-page">
                        <div class="card mb-0 shadow-none h-100">
                            <div class="card-body">
                                <div class="px-3">
                                    <h2 class="font-weight-semibold font-22 mb-2"> {{__('messages.welcome')}} <span class="text-primary">Itender</span>.</h2>
                                    <form method="POST" action="{{ route('admin.login') }}" class="form-horizontal auth-form my-4">
                                        @csrf
                                        <div class="form-group">
                                            <label for="user_name">Լոգին</label>
                                            <div class="input-group mb-3"><span class="auth-form-icon"><i data-feather="user" class="icon-sm"></i> </span>
                                            <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Լոգին" value="{{ old('user_name') }}">
                                                @error('user_name')
                                                    <span class="invalid-feedback" role="alert" style="display:block">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        
                                        </div>
                                        <!--end form-group-->
                                        <div class="form-group">
                                            <label for="password">Գաղտնաբառ</label>
                                            <div class="input-group mb-3"><span class="auth-form-icon"><i data-feather="key" class="icon-sm"></i> </span>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Գաղտնաբառ">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert" style="display:block">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            </div>
                                        </div>
                                        <!--end form-group-->
                                        <!-- <div class="form-group row mt-4">
                                            <div class="col-sm-6">
                                                <div class="custom-control custom-switch switch-success"><input type="checkbox" class="custom-control-input" id="customSwitchSuccess"> <label class="custom-control-label text-muted" for="customSwitchSuccess">Remember me</label></div>
                                            </div>
                                        </div> -->
                                        <!--end form-group-->
                                        <div class="form-group mb-0 row">
                                            <div class="col-12 mt-2"><button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light" >Մուտք<i class="fas fa-sign-in-alt ml-1"></i></button></div>
                                            <!--end col-->
                                        </div>
                                        <!--end form-group-->
                                    </form>
                                </div>
                                <div class="mt-3 text-center">&copy; 2019 - {{date("Y")}} Itender </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 p-0 h-100vh d-flex justify-content-center auth-bg">
                    <div class="accountbg d-flex align-items-center">
                        <div class="account-title text-center text-white">
                            <img src="/assets/back/assets/images/logo-sm.png" alt="" class="thumb-sm">
                            <h4 class="mt-3 text-white">Բարի գալուստ <span class="text-primary">Itender</span></h4>
                            {{-- <h1 class="text-white">Ադմինի վահանակ</h1> --}}
                            {{-- <p class="font-18 mt-3">Ադմինի վահանակ.</p> --}}
                            <div class="border w-25 mx-auto border-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery  --><script src="/assets/back/assets/js/jquery.min.js"></script><script src="/assets/back/assets/js/jquery-ui.min.js"></script><script src="/assets/back/assets/js/bootstrap.bundle.min.js"></script><script src="/assets/back/assets/js/metismenu.min.js"></script><script src="/assets/back/assets/js/waves.js"></script><script src="/assets/back/assets/js/feather.min.js"></script><script src="/assets/back/assets/js/jquery.slimscroll.min.js"></script><script src="../plugins/apexcharts/apexcharts.min.js"></script><!-- App js --><script src="/assets/back/assets/js/app.js"></script>
    </body>
</html>