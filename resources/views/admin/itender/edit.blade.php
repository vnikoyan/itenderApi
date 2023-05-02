@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Չափման միավորներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Չափման միավորներ  @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        {{ Form::model($blackList,array('route' => array('black_lists.update',$blackList->id),'class'=>'form-horizontal form-material mb-0'))}}
                                            @method('PUT')
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    {{ Form::text('name', $blackList->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                                </div>  
                                                
                                                <div class="col-md-4">
                                                    {{ Form::date('start_date', $blackList->start_date, ["class"=>"form-control","placeholder"=>"Սկսման ամսաթիվ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('start_date') }} </p>
                                                </div>  
                                                
                                                <div class="col-md-4">
                                                    {{ Form::date('end_date', $blackList->end_date, ["class"=>"form-control","placeholder"=>"Ավարտի ամսաթիվ"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('end_date') }} </p>
                                                </div>  
                                                
                                                <div class="col-md-4">
                                                    {{ Form::text('address', $blackList->address, ["class"=>"form-control","placeholder"=>"հասցեն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('address') }} </p>
                                                </div>  
                                                
                                                <div class="col-md-4">
                                                    {{ Form::textarea('info', $blackList->info, ["class"=>"form-control","placeholder"=>"տեղեկություն"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('info') }} </p>
                                                </div>  

                                                
                                                <div class="col-md-4">
                                                    {{ Form::textarea('for_what', $blackList->for_what, ["class"=>"form-control","placeholder"=>"Ինչի համար"])}}
                                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('for_what') }} </p>
                                                </div>  
                                            </div>
                                            <div class="form-group">
                                                <a href="/admin/package"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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

@stop