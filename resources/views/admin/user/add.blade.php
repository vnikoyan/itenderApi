@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Օգտատերեր @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') Օգտատերեր Ստեղծել @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($users,array('route' => array('user.store'),'class'=>'form-horizontal form-material mb-0'))}}
                                        @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    {{ Form::text('name', $users->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                                </div>
                                                <div class="col-md-3">
                                                    {{ Form::text('email', $users->email, ["class"=>"form-control","placeholder"=>"Էլ Փոստ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('email') }} </p>
                                                </div>
                                                <div class="col-md-3">
                                                    {{ Form::text('phone', $users->phone, ["class"=>"form-control","placeholder"=>"Հեռախոս"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('phone') }} </p>
                                                </div>

                                                <div class="col-md-3">
                                                    {{ Form::select('status',array('ACTIVE'=>'ACTIVE','BLOCK'=>'BLOCK'), $users->status, $attributes = array('class'=>'form-control'))}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('status') }} </p>
                                                </div>
                                                <div class="col-md-3">
                                                    {{ Form::text('expired', $users->expired, ["class"=>"form-control","placeholder"=>"Ժամկետ","id"=>"mdate"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('expired') }} </p>
                                                </div>
                                                <div class="col-md-3">
                                                    {{ Form::select('package_id',$package, $users->package_id, $attributes = array('class'=>'form-control'))}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('package_id') }} </p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    {{ Form::text('password', $users->password, ["class"=>"form-control","placeholder"=>"Գաղտնաբառ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password') }} </p>
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::text('password_confirmation', $users->password_confirmation, ["class"=>"form-control","placeholder"=>"Գաղտնաբառի հաստատում"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password_confirmation') }} </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/user"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                                                <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                                            </div>
                                            {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
@stop
@section('scripts')

@stop
