@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Գործընկերների @stop
@section('breadcrumb_active') Ստեղծել @stop

@section('page_title') Գործընկերներ  @stop

@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <style>
        .dropify-wrapper{
            width: 100%!important;
        }
    </style>
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($coworkers,array('route' => array('co_workers.store'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                                            @method('POST')
                                            <div class="form-row">
                                                <div class="col-md-4 mb-3">
                                                    <label for="address">Հասցե</label>
                                                    {{ Form::text('address', $coworkers->address, ["required"=>"required","class"=>"form-control","placeholder"=>"Հասցե"])}}
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="address">Գործունեության ոլորտը</label>
                                                    {{ Form::text('cpv', $coworkers->cpv, ["required"=>"required","class"=>"form-control","placeholder"=>"Գործունեության ոլորտը"])}}
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label for="address">Վեբ կայք</label>
                                                    {{ Form::text('website', $coworkers->website, ["required"=>"required","class"=>"form-control","placeholder"=>"www..."])}}
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <label for="" class="control-label">Օգտատերեր</label>
                                                    {{ Form::select('user_id',$users, $coworkers->user_id, $attributes = array("required"=>"required",'class'=>'form-control select2'))}}
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12"><br></div>
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4 class="mt-0 header-title"> Կցել նկար </h4>
                                                            <input type="file"  id="input-file-now-custom-2"  accept="image/*" name="image" class="dropify" >
                                                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('image') }} </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/co_workers"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>
@stop
