@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Ադմին @stop
@section('breadcrumb_active') Երաշխիքի հաշվետվություն @stop
@section('page_title') Երաշխիքի հաշվետվություն @stop
@section('content')
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="">Տարի</label>
                                <select class="form-control year-select" name="year">
                                    <option value="0" disabled selected> -- Ընտրել --</option>
                                    @foreach ($years as $year)
                                        <option value = "{{ $year['year'] }}">{{ $year['year'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label  class="">Ամիս</label>
                                <select class="form-control month-select" name="month">
                                    <option value="0" disabled selected> -- Ընտրել --</option>
                                    <option value="01" >Հունվար</option>
                                    <option value="02" >Փետրվար</option>
                                    <option value="03" >Մարտ</option>
                                    <option value="04" >Ապրիլ</option>
                                    <option value="05" >Մայիս</option>
                                    <option value="06" >Հունիս</option>
                                    <option value="07" >Հուլիս</option>
                                    <option value="08" >Օգոստոս</option>
                                    <option value="09" >Սեպտեմբեր</option>
                                    <option value="10" >Հոկտեմբեր</option>
                                    <option value="11" >Նոյեմբեր</option>
                                    <option value="12" >Դեկտեմբեր</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex">
                        <div class="col-md-3 bold text-center">
                            <h5>Բանկային երաշխիքով տենդերներ</h5>
                            <h6 class="bank-guaranteed-tenders">0</h6>
                        </div>
                        <div class="col-md-3 bold text-center">
                            <h5>www.itender.am -> www.vtb.am</h5>
                            <h6 class="itender-vtb">0</h6>
                        </div>
                        <div class="col-md-3 bold text-center">
                            <h5>Հայտերի քանակ</h5>
                            <h6 class="count-of-bid">0</h6>
                        </div>
                        <div class="col-md-3 bold text-center">
                            <h5>Մրցույթների քանակ</h5>
                            <h6 class="count-of-tenders">0</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
        <script>
            $(".year-select").change(function(){
                var year = $(".year-select").val();
                var month = $(".month-select").val();
                if( (year != null && month != null)){
                    getBankStats(year,month);
                }
            })

            $(".month-select").change( function(){
                var year = $(".year-select").val();
                var month = $(".month-select").val();
                console.log(month)
                if((year != null && month != null)){
                    getBankStats(year,month);
                }
            })

            function getBankStats(year,month){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "/admin/filter/getBankStats",
                    dataType: "json",
                    data: {year:year,month:month},
                    success: function(data){
                        $(".bank-guaranteed-tenders").text(data.guaranted_tenders);
                        $(".itender-vtb").text(data.banner);
                        $(".count-of-bid").text(data.guaranted);
                        $(".count-of-tenders").text(data.tenders);
                    }
                })
            }
        </script>
        <style>
            .bank-guaranteed-tenders, .itender-vtb, .count-of-bid, .count-of-tenders{
                border:3px solid #ffffff;
                padding:5px;
                font-weight: bold;
            }
        </style>
@stop