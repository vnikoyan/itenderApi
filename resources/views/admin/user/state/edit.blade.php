@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Օգտատերեր @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Օգտատերեր Խմբագրել @stop

@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<style>
    .dropify-wrapper{
        width: 100%!important;
    }
</style>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                        {{ Form::model($organisation,array('route' => array('admin.user.org',$organisation->id),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                            @method('PUT')
                            <input type="hidden" name="endDate" value=" {{ $endDate }} " class="end_date">
                            @isset($order->strat_date)
                                <input type="hidden" name="pStartDate" value=" {{  $order->strat_date }} " class="pStartDate">
                                <input type="hidden" name="pEndDate" value=" {{ $order->end_date }} " class="pEndDate">
                                <input type="hidden" name="orderId" value=" {{ $order->id }}">
                            @endisset
                            @isset($orderTrial->strat_date)
                                <input type="hidden" name="pStartDateTrial" value=" {{  $orderTrial->strat_date }} " class="pStartDateTrial">
                                <input type="hidden" name="pEndDateTrial" value=" {{ $orderTrial->end_date }} " class="pEndDateTrial">
                                <input type="hidden" name="orderIdTrial" value=" {{ $orderTrial->id }}">
                            @endisset
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label>Անուն</label>
                                    {{ Form::text('name', $organisation->translations['name']['hy'], ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                </div>

                                <div class="col-md-3">
                                    <label>Անուն Ռուսերեն</label>
                                    {{ Form::text('name_ru', $organisation->translations['name']['ru'], ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name_ru') }} </p>
                                </div>

                                <div class="col-md-3">
                                    <label>Հեռախոս</label>
                                    {{ Form::text('phone', $organisation->phone, ["class"=>"form-control","placeholder"=>"Հեռախոս"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('phone') }} </p>
                                </div>
                                <div class="col-md-3">
                                    <label>ՀՎՀՀ</label>
                                    {{ Form::text('tin', $organisation->tin, ["class"=>"form-control","placeholder"=>"ՀՎՀՀ"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('tin') }} </p>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="mt-0 header-title"> Պայմանագիր </h4>
                                            <input type="file" id="input-file-now-custom-2" name="contract" class="dropify" >
                                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('contract') }} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <h6 class="text-left">Ավելացնել փաթեթ</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                            <div class="col-md-3">
                                <label for="">փաթեթ</label>
                                <select class ="form-control package-sel" name="package_id">
                                    <option selected disabled> - Ընտրել - </option>
                                    @foreach($package as $p)
                                        @if(!empty($order))
                                            @if($order->package_id == $p->id)
                                                <option value="{{$p->id}}" selected> {{ $p->name }} </option>
                                            @else
                                                <option value="{{$p->id}}"> {{ $p->name }} </option>
                                            @endif
                                        @else
                                            <option value="{{$p->id}}"> {{ $p->name }} </option>
                                        @endif    
                                    @endforeach
                                </select>
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('package_id') }} </p>
                            </div>  
                            <div class="col-md-3">
                                <label for="">փաթեթի սկիզբ</label>
                                {{ Form::date('startDate', (!empty($order)) ? date("Y-m-d",strtotime($order->strat_date)): "" , ["class"=>"form-control",'type'=>'date',"placeholder"=>"սկիզբ - տարի/ամիս/օր","id"=>"startDate"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('startDates') }} </p>
                            </div> 
                            <div class="col-md-3 endDate-section">
                                <label for="">փաթեթի ավարտ</label>
                                {{ Form::date('endDate', (!empty($order)) ? date("Y-m-d", strtotime($order->end_date)) : "", ["class"=>"form-control","placeholder"=>" ավարտ - տարի/ամիս/օր","id"=>"endDate","readOnly" =>"readOnly"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('expired') }} </p>
                            </div>
                            </div>
                            <h6 class = "text-left">Ավելացնել փորձաշրջան</h6>
                            <div class="form-group row">
                            <div class="col-md-3">
                                <label for="">փաթեթ</label>
                                <select class ="form-control package-sel-trial" name="package_id_trial">
                                    <option selected disabled> - Ընտրել - </option>
                                    @foreach($package as $p)
                                        @if(!empty($orderTrial))
                                            @if($orderTrial->package_id == $p->id)
                                                <option value="{{$p->id}}" selected> {{ $p->name }} </option>
                                            @else
                                                <option value="{{$p->id}}"> {{ $p->name }} </option>
                                            @endif
                                        @else
                                            <option value="{{$p->id}}"> {{ $p->name }} </option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>  
                            <div class="col-md-3">
                                <label for="">փաթեթի սկիզբ</label>
                                {{ Form::date('startDate_trial', (!empty($orderTrial)) ? date("Y-m-d",strtotime($orderTrial->strat_date)): "" , ["class"=>"form-control",'type'=>'date',"placeholder"=>"սկիզբ - տարի/ամիս/օր","id"=>"startDate-trial"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('startDates') }} </p>
                            </div> 
                            <div class="col-md-3 endDate-section">
                                <label for="">փաթեթի ավարտ</label>
                                {{ Form::date('endDate_trial', (!empty($orderTrial)) ? date("Y-m-d", strtotime($orderTrial->end_date)) : "", ["class"=>"form-control","placeholder"=>" ավարտ - տարի/ամիս/օր","id"=>"endDate-trial"])}}
                                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('expired') }} </p>
                            </div>
                            </div>
                            <div class="form-group">
                                <a href="/admin/user_state"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                                <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">

                    @foreach($divisions as $key => $value)
                        <div class="col-lg-12 userblock ">
                            <div class="page-title-box">
                                <h4 class="page-title">
                                    {{$key}}
                                </h4>
                            </div>
                           @foreach($value as $k => $val)

                                <div class="card profile-card">
                                    <div class="card-body p-0">
                                        <div class="media p-3 align-items-center"
                                                style="display: flex;">
                                            <div class="media-body  align-self-center">
                                                <h5 class="pro-title textS"> {{$val->name}} </h5>
                                                <p class="mb-1 text-muted textS">{{$val->email}}</p>
                                                <p class="mb-1 text-muted textS">{{$val->username}}</p>
                                            </div>
                                            <div class="action-btn" data-id="{{$val->id}}"
                                                    style="position: inherit!important;display: flex;">
                                                <button class="mr-1 btnActive btn btn-sm btn-soft-info eye"
                                                        data-placement="top" title=""
                                                        data-original-title="Դիտել" data-trigger="hover"
                                                        type="button" data-toggle="modal"
                                                        data-target=".bs-example-modal-center_{{$val->id}}">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                                        <!--end row-->
                            <div class="modal fade bs-example-modal-center_{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title mt-0" id="exampleModalLabel"></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ Form::model($val,array('route' => array('user_state.update',$val->id),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                                                @method('PUT')
                                                <div class="form-group">
                                                    <div class="col mb-4">
                                                        <label style=" float: left; ">Անուն Ազգանուն</label>
                                                        <input class="form-control" placeholder="Անուն" name="name" type="text" required value="{{$val->name}}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label style=" float: left;">Մուտքանուն</label>
                                                        <input class="form-control" placeholder="Մուտքանուն" name="username" type="text" required value="{{$val->username}}">
                                                    </div>
                                                    <div class="col">
                                                        <label style=" float: left; ">Էլ-փոստ</label>
                                                        <input class="form-control" placeholder="Էլ Փոստ" name="email" type="email"  required value="{{$val->email}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-secondary btn-sm text-light px-4 mt-3 ml-4 float-right mb-0"> Պահպանել </button>
                                                </div>
                                            {{ Form::close() }}
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                                                @endforeach

                        </div>
                    @endforeach
                    </div>
                    <div class="col-lg-5">
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('c_type') }} </p>
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('c_name') }} </p>
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('c_username') }} </p>
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('c_email') }} </p>
                        <div class="btn-group" style="display: flex;">
                            <button type="button" id="parentDropdown" class="btn btn-primary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">Ընտրել
                            </button>
                            <div id="toggle-itmes" class="dropdown-menu" style="width: 100%;text-align: center;">
                                <a class="dropdown-item dropdownItem" data-type="4">
                                    Վարչություն
                                </a>
                                <a class="dropdown-item dropdownItem" data-type="3">
                                    Բաժին
                                </a>
                                <a class="dropdown-item dropdownItem" data-type="2">
                                    Համակարգող
                                </a>
                            </div>
                        </div>
                        <div class="row hidden toogle-block" style="display:none" id="addChild">
                            <div class="card dr-pro-pic mx-auto" style="margin:10px">
                                <div class="card-body" >
                                    <form action="/admin/user_state/addDivisions/{{$organisation->id}}" method="POST">
                                        @method('POST')
                                        {{ csrf_field() }}
                                        <h4 id="textForm"></h4>
                                        <input type="hidden" name="c_type" id="typeInput" value="">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label style=" float: left;">Անուն Ազգանուն</label>
                                                <input class="form-control" placeholder="Անուն"name="c_name" type="text" value="" required>
                                                <p style="color:red;height:14px;width:100%;">{{ $errors->first('name') }}</p>
                                            </div>
                                            <div class="col-md-12">
                                                <label style=" float: left;">Մուտքանուն</label>
                                                <input class="form-control" placeholder="Մուտքանուն" name="c_username" type="text" value="" required>
                                                <p style="color:red;height:14px;width:100%;">{{ $errors->first('username') }}</p>
                                            </div>
                                            <div class="col-md-12">
                                                <label style="float: left;">Էլ-փոստ</label>
                                                <input class="form-control" placeholder="Էլ-փոստ" name="c_email" type="email" value="" required>
                                                <p style="color:red;height:14px;width:100%;">{{ $errors->first('email') }}</p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-gradient-secondary btn-clipboard btn-sm text-light px-4 mt-3 float-right mb-0">
                                                Ստեղծել
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>
<script>
    $(".dropdownItem").on("click", function () {
        $("#addChild").hide();
        type = $(this).data("type");
        $("#typeInput").val(type);
        $("#parentDropdown").text($(this).text());
        $("#textForm").text($(this).text());
        $("#addChild").show();
    });

    $(".package-sel").change( function(){
        var pStartDate = $(".pStartDate").val();
        var pEndDate =   $(".pEndDate").val();
        var package = $(this).val();
        var orderPackage = {{ (isset($order->package_id)) ? $order->package_id : 0 }};
        var startDate =  moment().format('YYYY-MM-DD'); 
        var endDate = $(".end_date").val(); 
        endDate = moment(endDate).format('YYYY-MM-DD');
        if(orderPackage != package){
            $("#startDate").val(startDate);
            $("#endDate").val(endDate);
        }else{
            $("#startDate").val(moment(pStartDate).format('YYYY-MM-DD'));
            $("#endDate").val(moment(pEndDate).format('YYYY-MM-DD'));
        }
    })
    
    $(".package-sel-trial").change( function(){
        var pStartDate = $(".pStartDateTrial").val();
        var pEndDate =   $(".pEndDateTrial").val();
        var package = $(this).val();
        var orderPackage = {{ (isset($orderTrial->package_id)) ? $orderTrial->package_id : 0 }};
        if(orderPackage == package){
            $("#startDate-trial").val(moment(pStartDate).format('YYYY-MM-DD'));
            $("#endDate-trial").val(moment(pEndDate).format('YYYY-MM-DD'));
        }else{
            $("#startDate-trial").val(" ");
            $("#endDate-trial").val(" ");

        }
    })

    $("#startDate").change(function(){
        var startDate  = $(this).val();
        var date       = new Date();
        var year       = + startDate.split('-')[0];
        var month      = + startDate.split('-')[1];
        var day        = + startDate.split('-')[2];
        year = year + 1;
        var endDate =  year + '-' + month + '-' + day; 
        $("#endDate").val(moment(endDate).format('YYYY-MM-DD'));
    })
</script>
@stop
