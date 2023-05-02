@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Սահմանվող պահանջներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Սահմանվող պահանջներ  @stop

@section('content')
<!-- end page title end breadcrumb -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                    {{ Form::model($defined_requirements,array('route' => array('defined_requirements.store'),'class'=>'form-horizontal form-material mb-0'))}}
                        @method('POST')
                        <div class="form-group row">

                            <div class="col-md-4">
                                <label for="" class="">Անվանում</label>
                                {{ Form::text('title', $defined_requirements->title, ["class"=>"form-control","placeholder"=>"Անվանում"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title') }} </p>
                            </div>  
                            <div class="col-md-4">
                                <label for="" class="">Հերթականություն</label>
                                {{ Form::text('order', $defined_requirements->order, ["class"=>"form-control","placeholder"=>"Հերթականություն"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
                            </div>  
                            <div class="col-md-4">
                                <label for="" class="">Կատեգորիա</label>
                                {{ Form::select('cpv_id',[], $defined_requirements->cpv_id, array('class'=>'form-control select22'))}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('cpv_id') }} </p>
                            </div>  
                            <div class="col-md-12">
                                <label for="" class="">Նկարագրություն</label>
                                {!! Form::textarea('description', $defined_requirements->description, ['class'=>'form-control html' ,"placeholder"=>"Նկարագրություն "]) !!}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description') }} </p>
                            </div>  
                        </div>
                        <div class="form-group">
                            <a href="/admin/defined_requirements"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

<script>
$(".select22").select2({
    minimumInputLength: 2,
    ajax: {
        url: "/admin/defined_requirements/get_by_ajax",
        dataType: 'json',
        type: "GET",
        data: function (term) {
            return {
                term: term
            };
        },
        processResults: function (data) {
            console.log(data)
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        slug: item.name,
                        id: item.id
                    }
                })
            };
        }
    }
});
</script>
@stop