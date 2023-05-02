@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Ադմին @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') Ադմին Ստեղծել @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($admin,array('route' => array('admin.store'),'class'=>'form-horizontal form-material mb-0'))}}
                                        @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    {{ Form::text('name', $admin->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                                </div>  
                                                <div class="col-md-4">
                                                    {{ Form::text('user_name', $admin->user_name, ["class"=>"form-control","placeholder"=>"Լոգին"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('user_name') }} </p>
                                                </div>  
                                                <div class="col-md-4">
                                                    {{ Form::text('email', $admin->email, ["class"=>"form-control","placeholder"=>"Էլ Փոստ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('email') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    {{ Form::text('password', $admin->password, ["class"=>"form-control","placeholder"=>"Գաղտնաբառ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password') }} </p>
                                                </div>
                                                <div class="col-md-6">
                                                    {{ Form::text('password_confirmation', $admin->password_confirmation, ["class"=>"form-control","placeholder"=>"Գաղտնաբառի հաստատում"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password_confirmation') }} </p>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="table-responsive attendance-table">
                                                                        <table class="table table-bordered mb-0 table-centered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Գործառույթ</th>
                                                                                    <th>Իրավասություն</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($permission as $key => $value)
                                                                                    <tr>
                                                                                        <td>{{$key}}</td>
                                                                                        @foreach($value as $k => $val)
                                                                                        {{-- $admin->permission[$val->id] --}}
                                                                                           <td>{{ Form::checkbox('permission['.$val->id.']', "1",["class"=>""])}}</td>
                                                                                        @endforeach
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                        <!--end /table-->
                                                                    </div>
                                                                    <!--end /tableresponsive-->
                                                                </div>
                                                                <!--end card-body-->
                                                            </div>
                                                            <!--end card-->
                                                        </div>
                                                    </div>
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
                    <!--end row-->
@stop
@section('scripts')

@stop