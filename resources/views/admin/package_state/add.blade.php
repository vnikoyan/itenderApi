@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second')  Փաթեթ @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title')Ստեղծել  Փաթեթ  @stop
@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($packages,array('route' => array('package_state.store'),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="" class="">Անվանում</label>
                                                    <input type="text" name="name" class="form-control">
                                                    @if($errors->has('name'))
                                                        <div class="error">{{ $errors->first('name') }}</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="">արժեք</label>
                                                    <input type="number" name="price" class="form-control">
                                                    @if($errors->has('price'))
                                                        <div class="error">{{ $errors->first('price') }}</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                     <label for="" class="">Ժամկետ</label>
                                                    <input type="text" name="price" class="form-control" disabled value="1 տարի">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/package_state"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
