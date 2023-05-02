@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Չափման միավորներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Չափման միավորներ  @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($units,array('route' => array('units.store'),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('POST')
                                            <div class="form-group row">

                                                @foreach($language as $key => $value)
                                                    <div class="col-md-4">
                                                        {{ Form::text('units['.$value->code.']', $units->getTranslation("title",$value->code), ["class"=>"form-control","placeholder"=>"Անվանում ".$value->name])}}
                                                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('units.'.$value->code) }} </p>
                                                    </div>
                                                @endforeach

                                                <div class="col-md-4">
                                                    {{ Form::text('order', $units->order, ["class"=>"form-control","placeholder"=>"order"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/units"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
