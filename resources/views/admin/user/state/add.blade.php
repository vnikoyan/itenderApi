@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Պետական օգտատերեր @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') Պետական օգտատերեր Ստեղծել @stop

@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<style>
</style>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($users,array('route' => array('user_state.store'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                                        @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label>Անուն</label>
                                                    {{ Form::text('name', $users->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Էլ Փոստ</label>
                                                    {{ Form::text('email', $users->email, ["class"=>"form-control","placeholder"=>"Էլ Փոստ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('email') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Հեռախոս</label>
                                                    {{ Form::text('phone', $users->phone, ["class"=>"form-control","placeholder"=>"Հեռախոս"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('phone') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Parent</label>
                                                    {{ Form::select('parent_id',$usersStateRoot, $users->status, $attributes = array('class'=>'form-control'))}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('parent_id') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Type</label>
                                                    {{ Form::select('divisions',['DEPARTMENT'=>'ՎԱՐՉՈՒԹՅՈՒՆ', 'SECTION'=>'ԲԱԺԻՆ' ,'COORDINATOR'=>'ՀԱՄԱԿԱՐԳՈՂ' ,'RESPONSIBLE_UNIT'=>'ՊԱՏԱՍԽԱՆԱՏՈՒ ՍՏՈՐԱԲԱԺԱՆՈՒՄ'], $users->divisions, $attributes = array('class'=>'form-control'))}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('divisions') }} </p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/user_state"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>

@stop
