@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Փաթեթ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Փաթեթ Խմբագրել @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($packages,array('route' => array('package.update',$packages->id),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('PUT')
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    {{ Form::text('price_1', $packages->price_1, ["class"=>"form-control","placeholder"=>"1 ամիս"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('price_1') }} </p>
                                                </div>  
                                                <div class="col-md-3">
                                                    {{ Form::text('price_3', $packages->price_3, ["class"=>"form-control","placeholder"=>"3 ամիս"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('price_3') }} </p>
                                                </div>  
                                                <div class="col-md-3">
                                                    {{ Form::text('price_6', $packages->price_6, ["class"=>"form-control","placeholder"=>"6 ամիս"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('price_6') }} </p>
                                                </div>  
                                                <div class="col-md-3">
                                                    {{ Form::text('price_12', $packages->price_12, ["class"=>"form-control","placeholder"=>"1 տարի"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('price_6') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/package"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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