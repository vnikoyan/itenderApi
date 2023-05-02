<!-- end page title end breadcrumb -->
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                    {{ Form::model($admin,array('route' => array('admin.update', $admin->id),'class'=>'form-horizontal adminForm form-material mb-0'))}}
                        @method('PUT')
                        <div class="form-group row">
                            <div class="col-md-4">
                                {{ Form::text('name', $admin->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                <p style="color:red;height: 14px;width: 100%;" id="error_name"> {{ $errors->first('name') }} </p>
                            </div>  
                            <div class="col-md-4">
                                {{ Form::text('user_name', $admin->user_name, ["class"=>"form-control","placeholder"=>"Լոգին"])}}
                                <p style="color:red;height: 14px;width: 100%;" id="error_user_name"> {{ $errors->first('user_name') }} </p>
                            </div>  
                            <div class="col-md-4">
                                {{ Form::text('email', $admin->email, ["class"=>"form-control","placeholder"=>"Էլ Փոստ"])}}
                                <p style="color:red;height: 14px;width: 100%;" id="error_email"> {{ $errors->first('email') }} </p>
                            </div>  
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                {{ Form::text('password',"", ["class"=>"form-control","placeholder"=>"Գաղտնաբառ"])}}
                                <p style="color:red;height: 14px;width: 100%;" id="error_password"> {{ $errors->first('password') }} </p>
                            </div>
                            <div class="col-md-6">
                                {{ Form::text('password_confirmation', $admin->password_confirmation, ["class"=>"form-control","placeholder"=>"Գաղտնաբառի հաստատում"])}}
                                <p style="color:red;height: 14px;width: 100%;" id="error_password_confirmation"> {{ $errors->first('password_confirmation') }} </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="/admin/admin"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                            <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                        </div>
                       {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>
