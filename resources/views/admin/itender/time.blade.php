@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Տենդերների հրապարակման ժամկետ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Տենդերների հրապարակման ժամկետ  @stop
@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div    class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($itenderTerms,array('route' => array('itender.timeUpdate'),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('POST')
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <label for="example-text-input" class="col-sm-12 col-form-label text-right">Տենդերների հրապարակման նվազագույն ժամկետը (օր)</label>
                                                    {{ Form::number('min', $itenderTerms->min, ["class"=>"form-control inputNuber","placeholder"=>"Տենդերների հրապարակման նվազագույն ժամկետը (օր)"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('min') }} </p>
                                                </div>  
                                                <div class="col-md-6">
                                                <label for="example-text-input" class="col-sm-12 col-form-label text-right">Տենդերների պայմանների փոփոխության ժամկետը (օր)</label>
                                                    {{ Form::number('max', $itenderTerms->max, ["class"=>"form-control inputNuber dateC","placeholder"=>"Տենդերների պայմանների փոփոխության ժամկետը (օր)"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('max') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
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
    $(".inputNuber").on("change", function () {
        if($(this).val()  < 0){
            $(this).val(0) 
        }
    });
</script>
@stop