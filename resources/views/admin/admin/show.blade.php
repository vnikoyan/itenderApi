<!-- end page title end breadcrumb -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-md-6">
                                {{ Form::text('name', $admin->name, ["disabled"=>"disabled","class"=>"form-control","placeholder"=>"Name"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                            </div>  
                            <div class="col-md-6">
                                {{ Form::text('email', $admin->email, ["disabled"=>"disabled","class"=>"form-control","placeholder"=>"E-mail"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('email') }} </p>
                            </div>  
                        </div>
                        <div class="form-group">
                            <a href="/admin/admin"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Փակել</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
