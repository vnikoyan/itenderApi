@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') էջ @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') էջ Ստեղծել @stop

@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<style>
        .dropify-wrapper{
            width: 100%!important;
        }
    </style>
<div class="row">
  <div class="col-lg-12 mx-auto">
      <div class="card dr-pro-pic">
          <div class="card-body">
              <div class="">
                        {{ Form::model($pages,array('class'=>'form-horizontal form-material mb-0'))}}
                          <div class="form-group row">
                              <div class="col-md-4">
                                  {{ Form::text('title', $pages->title, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title') }} </p>
                              </div>  
                              <div class="col-md-4">
                                  {{ Form::text('slug', $pages->slug, ["class"=>"form-control","placeholder"=>"slug"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('slug') }} </p>
                              </div>  
                              <div class="col-md-4">
                                  {{ Form::text('order', $pages->order, ["class"=>"form-control","placeholder"=>"order"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
                              </div>  
                          </div>
                          <div class="form-group row">
                              <div class="col-md-4">
                                  {{ Form::text('meta_title', $pages->meta_title, ["class"=>"form-control","placeholder"=>"meta_title"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('meta_title') }} </p>
                              </div>  
                              <div class="col-md-4">
                                  {{ Form::text('meta_description', $pages->meta_description, ["class"=>"form-control","placeholder"=>"meta_description"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('meta_description') }} </p>
                              </div>  
                              <div class="col-md-4">
                                  {{ Form::text('meta_key', $pages->meta_key, ["class"=>"form-control","placeholder"=>"meta_key"])}}
                                  <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('meta_key') }} </p>
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
</div>

    @stop
    @section('scripts')
            <!-- PAGE RELATED PLUGIN(S) -->

    <script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

    
    <script type="text/javascript">
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
 


    </script>
@stop