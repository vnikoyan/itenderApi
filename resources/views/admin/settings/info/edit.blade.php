@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Տեղեկատվություն @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Տեղեկատվություն  @stop

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
                                        {{ Form::model($info,array('route' => array('info.update',$info->id),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                                            @method('PUT')
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="form-group row">
                                                        @foreach($language as $key => $value)
                                                            <div class="col-md-3">
                                                                {{ Form::text('title['.$value->code.']', $info->getTranslation("title",$value->code), ["class"=>"form-control","placeholder"=>"Անվանում ".$value->name])}}
                                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title.'.$value->code) }} </p>
                                                            </div>  
                                                            <div class="col-md-9">
                                                                {!! Form::textarea('description['.$value->code.']', $info->getTranslation("description",$value->code), ['class'=>'form-control html' ,"placeholder"=>"Նկարագրություն ".$value->name]) !!}
                                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$value->code) }} </p>
                                                            </div>  
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    {{ Form::number('order', $info->order, ["class"=>"form-control","placeholder"=>"order"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/info"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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

<script>
    $("#youtube_link").on("change", function () {
        $("#iframeYoutube").attr("src",$(this).val());
    });
    
    
</script>
@stop