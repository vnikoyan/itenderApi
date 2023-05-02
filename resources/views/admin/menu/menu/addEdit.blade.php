@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Մենյու @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') Մենյու Ստեղծել @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($menus,array('route' => array('admin.menu.create'),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    {{ Form::text('name', $menus->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/menu"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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