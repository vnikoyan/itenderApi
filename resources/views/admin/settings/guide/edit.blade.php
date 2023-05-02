@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Ուղեցույցներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Ուղեցույցներ  @stop

@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<style>
    .dropify-wrapper{
        width: 100%!important;
    }
</style>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-body">
            {{ Form::model($guide,array('route' => array('guide.update',$guide->id),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                @method('PUT')
                <div class="accordion" id="accordionExample">
                    @foreach($language as $key => $value)
                        <div class="card border mb-1 shadow-none">
                            <div class="card-header custom-accordion" id="{{$key}}">
                                <a href="" class="text-dark" style="display: block;" data-toggle="collapse" data-target="#{{$value->code}}" aria-expanded="true" aria-controls="{{$value->code}}">
                                    {{$value->name}}
                                </a>
                            </div>
                            <!-- show -->
                            @if ($errors->any())
                                <div id="{{$value->code}}" class="collapse show" aria-labelledby="{{$key}}" data-parent="#accordionExample" style="">
                            @else
                                    <div id="{{$value->code}}" class="collapse " aria-labelledby="{{$key}}" data-parent="#accordionExample" style="">
                            @endif

                                <div class="card-body">
                                    <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="" class="">Անվանում {{$value->name}}</label>
                                                {{ Form::text('title['.$value->code.']', $guide->getTranslation("title",$value->code), ["class"=>"form-control","placeholder"=>"Անվանում ".$value->name])}}
                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title.'.$value->code) }} </p>
                                            </div>
                                            <div class="col-md-9">
                                                <label for="" class="">Նկարագրություն {{$value->name}}</label>
                                                {!! Form::textarea('description['.$value->code.']', $guide->getTranslation("description",$value->code), ['class'=>'form-control html' ,"placeholder"=>"Նկարագրություն ".$value->name]) !!}
                                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$value->code) }} </p>
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="" class="">Youtube {{$value->name}}</label>
                                            {{ Form::text('youtube_link['.$value->code.']', $guide->getTranslation("youtube_link",$value->code), ["class"=>"form-control youtube_link","placeholder"=>"youtube_link","data-id"=>$value->code])}}
                                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('youtube_link.'.$value->code) }} </p>
                                        </div>
                                        <div class="col-xl-9">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="mt-0 header-title"> Տեսանյութ {{$value->name}}</h4>
                                                    <iframe width="100%" height="315" src='{{$guide->getTranslation("youtube_link",$value->code)}}' id="iframeYoutube_{{$value->code}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xl-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="mt-0 header-title"> Կցել ֆայլ </h4>
                                                    <input type="file" id="input-file-now-custom-2" name="file[{{$value->code}}]" class="dropify" >
                                                    <p style="color:red;height: 14px;width: 100%;">  {{ $errors->first('file.'.$value->code) }} </p>
                                                    <a href='/admin/guide/file/{{ $guide->id }}/{{$value->code}}' class="btn"><i  data-feather="download" class="align-self-center menu-icon icon-dual"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group">
                        <a href="/admin/guide"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

<script>
    $(".youtube_link").on("change", function () {
        $("#iframeYoutube_"+$(this).data("id")).attr("src",$(this).val());
    });

</script>
@stop
