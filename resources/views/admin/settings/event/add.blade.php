@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Նորություններ @stop
@section('breadcrumb_active') Ստեղծել @stop
@section('page_title') Նորություններ  @stop

@section('content')
    <link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <style>
        .dropify-wrapper{
            width: 100%!important;
        }
    </style>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                        {{ Form::model($event,array('route' => array('event.store'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                            @method('POST')
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        @foreach($language as $key => $value)
                                            <div class="col-md-3">
                                                {{ Form::text('title['.$value->code.']', $event->getTranslation("title",$value->code), ["class"=>"form-control","placeholder"=>"Անվանում ".$value->name])}}
                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title.'.$value->code) }} </p>
                                            </div>
                                            <div class="col-md-9">
                                                {!! Form::textarea('description['.$value->code.']', $event->getTranslation("description",$value->code), ['class'=>'form-control html' ,"placeholder"=>"Նկարագրություն ".$value->name]) !!}
                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$value->code) }} </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    {{ Form::text('youtube_link', $event->youtube_link, ["class"=>"form-control","placeholder"=>"youtube_link","id"=>"youtube_link"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('youtube_link') }} </p>
                                </div>
                                <div class="col-xl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title"> Տեսանյութ </h4>
                                            <iframe width="100%" height="315" src="{{$event->youtube_link}}" id="iframeYoutube" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                    {{ Form::text('order', $event->order, ["class"=>"form-control","placeholder"=>"order"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
                                </div>
                                <div class="col-xl-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title"> Կցել նկար </h4>
                                            <input type="file" multiple id="input-file-now-custom-2"  accept="image/*" name="image[]" class="dropify" >
                                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('image') }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title"> Կցել video </h4>
                                            <input type="file"  id="input-file-now-custom-3" name="video" accept="video/*" class="dropify" >
                                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('video') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="checkbox"  class="manager-checkbox" name="is_article" {{  ($event->is_article == 0) ? "" : "checked"}} value="1">
                                    <label for="is_article">Հոդված</label>
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('is_article') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="/admin/event"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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

    $("#youtube_link").change();
</script>
@stop
