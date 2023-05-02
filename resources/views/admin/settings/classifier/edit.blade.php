@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Ֆինանսական դասակարգչի @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Ֆինանսական դասակարգչի  @stop
@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-12 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($classifier,array('route' => array('classifier.update',$classifier->id),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('PUT')
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <label for="" class="">Անվանում</label>
                                                    {{ Form::text('title', $classifier->title, ["class"=>"form-control","placeholder"=>"Անվանում"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="">Ծածկագիր</label>
                                                    {{ Form::text('code', $classifier->code, ["class"=>"form-control","placeholder"=>"Ծածկագիր"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('code') }} </p>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="" class="">Կատեգորիա</label>
                                                    {{ Form::select('cpv_id[]',$cpv,$classifier->cpv()->pluck('cpv_id'), array('multiple'=>'multiple','class'=>'form-control select22'))}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('cpv_id') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/classifier"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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