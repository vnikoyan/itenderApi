@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Օգտատերեր @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Օգտատերեր Խմբագրել @stop

@section('content')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                        {{ Form::model($user,array('route' => array('user.update',$user->id),'class'=>'form-horizontal form-material mb-0'))}}
                            @method('PUT')
                            <input type="hidden" name="orderId" value = {{ (isset($order->id)) ? $order->id : 0  }}>
                            <input type="hidden" name="user_state_org" value = {{ (!empty($user->usgId)) ? $user->usgId : null  }}>
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label for="">Անուն</label>
                                    {{ Form::text('name', $user->name, ["class"=>"form-control","placeholder"=>"Անուն"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('name') }} </p>
                                </div>  
                                <div class="col-md-3">
                                    <label for="">Էլ. փոստ</label>
                                    {{ Form::text('email', $user->email, ["class"=>"form-control","placeholder"=>"Էլ. փոստ"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('email') }} </p>
                                    @if (\Session::has('message'))
                                        <div class="alert alert-danger">
                                            <ul>
                                                <li>{!! \Session::get('message') !!}</li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>  
                                <div class="col-md-3">
                                    <label for="">Հեռախոսահամար</label>
                                    {{ Form::text('phone', $user->phone, ["class"=>"form-control","placeholder"=>"Հեռախոսահամար"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('phone') }} </p>
                                </div>
                                @if(!empty($user->tin))  
                                <div class="col-md-3">
                                    <label for="">ՀՎՀՀ</label>
                                    {{ Form::text('tin', $user->tin, ["class"=>"form-control","placeholder"=>"ՀՎՀՀ"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('tin') }} </p>
                                </div>
                                @else
                                <div class="col-md-3">
                                    <label for="">Սոց. քարտի համար</label>
                                    {{ Form::text('id_card_number', $user->id_card_number, ["class"=>"form-control","placeholder"=>"Սոց. քարտի համար"])}}
                                </div>
                                <div class="col-md-3">
                                    <label for="">Անձնագիր</label>
                                    {{ Form::text('passport_serial_number', $user->passport_serial_number, ["class"=>"form-control","placeholder"=>"Անձնագիր"])}}
                                </div>
                                @endif  
                                <div class="col-md-3">
                                    <label for="">կարգավիճակ</label>
                                    {{ Form::select('status',array('ACTIVE'=>'ACTIVE','BLOCK'=>'BLOCK'), $user->status, $attributes = array('class'=>'form-control'))}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('status') }} </p>
                                </div>  
                                <div class="col-md-3">
                                    <label for="">փաթեթի սկիզբ</label>
                                    {{ Form::date('startDate', (isset($order->strat_date)) ? date("Y-m-d",strtotime($order->strat_date)) : "" , ["class"=>"form-control",'type'=>'date',"placeholder"=>"սկիզբ - տարի/ամիս/օր","id"=>"startDate"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('startDates') }} </p>
                                </div> 
                                <div class="col-md-3">
                                    <label for="">փաթեթ</label>
                                    {{ Form::select('package_id',$package, (isset($order->package_id)) ? $order->package_id : "", $attributes = array('class'=>'form-control package-sel'))}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('package_id') }} </p>
                                </div>
                                <div class="col-md-3 endDate-section">
                                    <label for="">փաթեթի ավարտ</label>
                                    {{ Form::date('endDate', (isset($order->end_date)) ? date("Y-m-d",strtotime($order->end_date)): "", ["class"=>"form-control","placeholder"=>" ավարտ - տարի/ամիս/օր","id"=>"endDate"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('expired') }} </p>
                                </div>
                                <div class="col-md-3 prices-sel-section" style = 'display: none;'>
                                    <label for="">փաթեթի ժամկետ </label>
                                    <input type="hidden" name="month" class="p-m-d">
                                    <select class="form-control prices-sel" required="required">
                                        <option>-- Ընտրել ժամկետը --</option>
                                        @foreach($prices as $price)
                                            <option value="{{$price->price_1}}" data-key="{{$price->name}}" data-month="1" style ="display: none;">1 ամիս - {{$price->price_1}} դրամ</option>
                                            <option value="{{$price->price_3}}" data-key="{{$price->name}}" data-month="3" style ="display: none;">3 ամիս - {{$price->price_3}} դրամ</option>
                                            <option value="{{$price->price_6}}" data-key="{{$price->name}}" data-month="6" style ="display: none;"> 6 ամիս -{{$price->price_6}} դրամ</option>
                                            <option value="{{$price->price_12}}" data-key="{{$price->name}}" data-month="12" style ="display: none;">1 տարի - {{$price->price_12}} դրամ</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class = "col-md-12 mb-5">
                                    <h6 class = "text-left">Ավելացնել փորձաշրջան</h6>
                                    <div class = "row">
                                        <div class = "col-md-4">
                                            <input type="hidden" name="trial_order_id" value="{{ (isset($trial_order->id) ? $trial_order->id : 0) }}">
                                            <label for="">Փաթեթ</label>
                                            {{ Form::select('trial_package_id',$package, (isset($trial_order->package_id)) ? $trial_order->package_id : "", $attributes = array('class'=>'form-control'))}}
                                        </div>
                                        <div class = "col-md-4">
                                            <label for="">Փորձաշրջանի սկիզբ</label>
                                            {{ Form::date('trial_startDate', (isset($trial_order->strat_date)) ? date("Y-m-d",strtotime($trial_order->strat_date)) : "" , ["class"=>"form-control",'type'=>'date',"placeholder"=>"սկիզբ - տարի/ամիս/օր","id"=>"startDate"])}}
                                        </div>
                                        <div class = "col-md-4">
                                            <label for="">Փորձաշրջանի ավարտ</label>
                                                {{ Form::date('trial_endDate', (isset($trial_order->end_date)) ? date("Y-m-d",strtotime($trial_order->end_date)) : "" , ["class"=>"form-control",'type'=>'date',"placeholder"=>"սկիզբ - տարի/ամիս/օր","id"=>"startDate"])}}
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    {{ Form::text('password', "", ["class"=>"form-control","placeholder"=>"Գաղտնաբառ"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password') }} </p>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::text('password_confirmation', "", ["class"=>"form-control","placeholder"=>"Գաղտնաբառի հաստատում"])}}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('password_confirmation') }} </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input type="checkbox"  class="manager-checkbox" name="is_manager" {{  ($user->is_manager == 0) ? "" : "checked"}} value="{{ $user->is_manager}}">
                                    <label for="">Կազմակերպիչ</label>
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('is_manager') }}
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="/admin/user"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
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
<script type="text/javascript">

    $("#startDate").change( function (){
        $(" .endDate-section").hide();
        $(".prices-sel-section").show();
        var price = $('.package-sel').find(":selected").text().trim();
        $( ".prices-sel option" ).each(function() {
          if( $( this ).attr('data-key') != price){
            $(this).hide();
          }else{
            $(this).show(); 
          }
        });
    })

    $(".package-sel").change( function (){
        $(" .endDate-section").hide();
        $(".prices-sel-section").show();
        var price = $('.package-sel').find(":selected").text().trim();
        $( ".prices-sel option" ).each(function() {
          if( $( this ).attr('data-key') != price){
            $(this).hide();
          }else{
            $(this).show(); 
          }
        });
    })

    $(".prices-sel").change(function(){
       var month = $(".prices-sel option:selected").data('month');
       $(".p-m-d").val(month);
    })

    $(".manager-checkbox").change(function(){
        if($(this).is(":checked")){
            $(this).val(1)
        }else{
            $(this).val(0)
        }
    })
</script>
@stop