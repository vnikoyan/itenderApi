@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Ադմին @stop
@section('breadcrumb_active') Ուղարկել էլ․ նամակ @stop
@section('page_title') Ուղարկել էլ․ նամակ @stop

@section('content')
{{ Form::model(array('route' => array('admin.sendMessage'),'class'=>'form-horizontal form-material mb-0'))}}
@method('post')
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body row">
            	<div class="col-md-12">
                	@if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif
                </div>
            	<div class="col-md-6 form-group">
                    <label  class="">Կատեգորիա</label>
                    <select class=" select2 form-control" name="category">
                    	<option value ="" disabled selected> -- Ընտրել -- </option>
                    	<option value ="0"> - Բոլորին - </option>
                    	@foreach($categories as $category)
                    	<option value = {{$category->id}}>{{ $category->code .' - '.$category->name }}</option>
                    	@endforeach
                    </select>
                    @error('category')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="">Ընտրել օգտատերերին</label>
                    <select class=" select2 form-control" name="userType">
                        <option value ="" disabled selected> -- Ընտրել -- </option>
                        <option value = "1">Բոլոր օգտատերերին ({{$userTypeCounts['userType1Count']}} օգտատեր)</option>
                    	<option value = "2">Պետական օգտատերերին ({{$userTypeCounts['userType2Count']}} օգտատեր)</option>
                    	<option value = "3">Մասնավոր օգտատերերին ({{$userTypeCounts['userType3Count']}} օգտատեր)</option>
                    	<option value = "4">Մասնավորի Էկոնոմ փաթեթից օգտվողներին ({{$userTypeCounts['userType4Count']}} օգտատեր)</option>
                    	<option value = "5">Մասնավորի Պրեմիում փաթեթից օգտվողներին ({{$userTypeCounts['userType5Count']}} օգտատեր)</option>
                    	<option value = "6">Մասնավորի Գոլդ փաթեթից օգտվողներին ({{$userTypeCounts['userType5Count']}} օգտատեր)</option>
                    	<option value = "7">Մասնավորի Փորձաշրջանի մեջ գտնվողներին ({{$userTypeCounts['userType6Count']}} օգտատեր)</option>
                        <option value = "8">Պետականի Էկոնոմ փաթեթից օգտվողներին ({{$userTypeCounts['userType8Count']}} օգտատեր)</option>
                    	<option value = "9">Պետականի Պրեմիում փաթեթից օգտվողներին ({{$userTypeCounts['userType9Count']}} օգտատեր)</option>
                    	<option value = "10">Պետականի Գոլդ փաթեթից օգտվողներին ({{$userTypeCounts['userType10Count']}} օգտատեր)</option>
                    	<option value = "11">Պետականի Փորձաշրջանի մեջ գտնվողներին ({{$userTypeCounts['userType11Count']}} օգտատեր)</option>
                    	<option value = "12">Կատեգորիա չընտրածներ ({{$userTypeCounts['userType12Count']}} օգտատեր)</option>
                    </select>
                    @error('userType')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 form-group">
                    <label for="" class="">Վերնագիր</label>
                    <input type="text" name="title" class="form-control" placeholder="Վերնագիր" value="{{ old('title') }}">
                    @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 form-group">
                    <label for="" class="">Բովանդակություն</label>
                    <textarea  style = "resize: none;height: 200px; "name="text" class="form-control html">{{ old('text') }}</textarea>
                    @error('text')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 form-group">
                    <label for="" class="">Այլ ստացողներ </label>
                    <textarea  style = "resize: none;height:120px;"name="additionals" class="form-control" value="{{ old('additionals') }}" placeholder="Խնդրում ենք էլ․ հասցեները իրարից առանձնացնել ստորակետերով"></textarea>
                    @error('additionals')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-10 form-group">
                	<button class="btn btn-primary">ՈՒղարկել</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop
@section('scripts')
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>
@stop