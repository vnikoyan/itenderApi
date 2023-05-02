@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Հայտարարություններ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Հայտարարություններ  @stop

@section('content')
    <style>
    .jstree-anchor{
        display: inline-block;
        max-width: 97%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .select2-selection--multiple{
        min-height: 62px;
        border: 1px solid #e8ebf3!important;
    }
    .view-sel, .cpv-name{
        resize: none;
    }
    .cpv-table{
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
          margin-bottom: 20px;
    }
    .cpv-table tr th{
          text-align: center;
    }
    .cpv-table td, .cpv-table th{
          border: 1px solid #dddddd;
          text-align: center;
          padding: 8px;
    }
    .cpv-table tr {
        background-color: #f1f5fa;
    }
    .cpv-table input {
        width: 100%;
    }
    .cpv-table textarea {
        resize: none;
        width: 100%;
        height: 62px;
        border: 1px solid #e8ebf3;
        margin-top: 9px;
    }
    .not-exists td{
        background-color: #f500001f;
    }
    .remove-all-cpv{
        background-color: #f500001f;
        padding: 15px;
        box-shadow: 5px 5px 5px;
        border-radius: 10px;
        cursor: pointer;
    }
    .estimatedPrice-section{
        display: none;
    }
    </style>
    <link href="/assets/back/plugins/treeview/themes/default/style.css" rel="stylesheet">

    <link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <style>
        .dropify-wrapper{
            width: 100%!important;
        }
    </style>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                        {{ Form::model($tenderState,array('route' => array('tender_state.adminUpdateManagerTender',$tenderState->id),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                            @method('POST')
                            <input type="hidden"  name="previous"  value='{{ ($tenderState->previous ? Input::old("previous") :  url()->previous()) }}  '>
                            <input type="hidden" name="tender_id" value="{{ $id }}">
                            <input type="hidden" name="cpvsCount" class= "cpvsCount" value='{{ count($cpvAttributes)}}'>
                            @if($tenderState->cpv != "0")
                                <input type="hidden" name="tp_name" class ="tp_name" value="{{ $tenderState->type_name}}" 
                            @elseif($tenderState->category != "0")
                            <input type="hidden" name="cpvOrCategory" value="category" class= "cpvOrCategory">
                            @endif
                            <div class="form-group row">
                            <div class="col-md-10">
                                    <label for="" class="">Պատվիրատու<span style="color:red;font-size:12px">*</span></label>
                                    <select class="select2 form-control organizer-sel" name="organizator">
                                        <option value="" disabled selected>{{ $tenderState->customer_name}}</option>
                                    </select>
                                    <p style="color:red;width: 100%;"> {{ $errors->first('organizator') }} </p>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group row">
                                            <div class="col-md-10">
                                                <label for="" class="">Անվանում hy<span style="color:red;font-size:12px">*</span></label>
                                                {{ Form::text('title', $tenderState->title, ["required"=>"required","class"=>"form-control","placeholder"=>"Անվանում hy"]) }}
                                            </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="" class="" style ="display:block;">Սկիզբ<span style="color:red;font-size:12px">*</span></label>
                                            <input type="date" name="start_date" required placeholder="Սկիզբ" class="form-control" value="{{ date("Y-m-d",strtotime($tenderState->start_date))}}">
                                            <input type="text" name="start_time" required placeholder="13:00" class="form-control" value="{{ date("H:i",strtotime($tenderState->start_date))}}">
{{--                                             
                                            <label for="" class="">Սկիզբ<span style="color:red;font-size:12px">*</span></label>
                                            {{ Form::dateTime('start_date', $tenderState->start_date, ["required"=>"required","class"=>"form-control startDataTime","placeholder"=>"Սկիզբ"])}} --}}
                                            <p style="color:red;width: 100%;"> {{ $errors->first('start_date') }} </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="" style ="display:block;">Ավարտ<span style="color:red;font-size:12px">*</span></label>
                                            <input type="date" name="end_date" required placeholder="Սկիզբ" class="form-control" value="{{ date("Y-m-d",strtotime($tenderState->end_date))}}">
                                            <input type="text" name="end_time" required placeholder="14:00" class="form-control" value="{{ date("H:i",strtotime($tenderState->end_date))}}">
                                            {{-- <label for="" class="">Ավարտ<span style="color:red;font-size:12px">*</span></label>
                                            {{ Form::dateTime('end_date', $tenderState->end_date, ["required"=>"required","class"=>"form-control endDataTime","placeholder"=>"Ավարտ"])}}
                                            <p style="color:red;width: 100%;"> {{ $errors->first('end_date') }} </p> --}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <label for="" class="">Մարզեր<span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                            <select class="select2 form-control" required="required" name="regions" id="tender-region">
                                                <option value="">-- Ընտրել --</option>
                                                @foreach($regions as $val)
                                                <option {{ ($tenderState->regions == $val->id ? "selected":"") }} value={{ $val->id }}>{{ $val->name}}</option>
                                                @endforeach
                                            </select>
                                            <p style="color:red;width: 100%;"> {{ $errors->first('regions') }} </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="" class="">էլեկտրոնային թղթային<span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                            <select class="select2 form-control tender-type" required="required" name="type">
                                                <option value="" disabled>-- Ընտրել --</option>
                                                <option {{ ($tenderState->type == "PAPER" ? "selected":"") }} value=" PAPER">Թղթային</option>
                                                <option {{ ($tenderState->type == "ELECTRONIC" ? "selected":"") }} value="ELECTRONIC">Էլեկտրոնային (armeps)</option>
                                                <option {{ ($tenderState->type == "ELECTRONIC AUCTION" ? "selected":"") }} value=" ELECTRONIC AUCTION">Էլեկտրոնային աճուրդ</option>
                                            </select>
                                            <p style="color:red;width: 100%;"> {{ $errors->first('type') }} </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="" class="">երաշխիքով-առանց երաշխիք<span style="color:red;font-size:12px">*</span></label>
                                            <select class="select2 form-control" required="required" name="guaranteed">
                                                <option value="">-- Ընտրել --</option>
                                                <option {{ ($tenderState->guaranteed == "1" ? "selected":"") }} value="1">Երաշխիքով</option>
                                                <option {{ ($tenderState->guaranteed == "0" ? "selected":"") }} value="0">Առանց երաշխիքի</option>
                                            </select>
                                            <p style="color:red;width: 100%;"> {{ $errors->first('guaranteed') }} </p>
                                        </div>
                                        <div class="col-md-12">
                                            <table class="cpv-table">
                                              <tr>
                                                <th style = "width: 20%;">Չափաբաժնի համարը</th>
                                                <th style = "width: 50%;">
                                                    CPV<span style="color:red;font-size:12px">*  
                                                </th>
                                                <th style = "width: 30%;">
                                                    Գնման առարկայի անվանումը
                                                </th>
                                                <th>
                                                    Նախահաշվարկային գին
                                                </th>
                                                <th>
                                                    Գործողություն
                                                </th>
                                              </tr>
                                              <tr>
                                                <th style = "width: 20%;">
                                                    <textarea class="view-id-inp" type = "text" ></textarea>
                                                </th>
                                                <th style = "width: 50%;">
                                                    <select class="select2 form-control" id="cpvSelect" required="required" name="cpv[]" multiple>
                                                        @foreach($tenderState->getCpv as $value)
                                                            @if(!empty($value->cpv))
                                                                <option value="{{$value->cpv->id}}" selected> {{$value->cpv->code}} </option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                                <p style="color:red;width: 100%;"> {{ $errors->first('cpv') }} </p> 
                                                </th>
                                                  <th style = "width: 20%;">
                                                      <textarea class="cpv-name-inp" type = "text" ></textarea>
                                                  </th>
                                                  <th>

                                                  </th>
                                                    <th>
                                                    <i class='fa fa-ban remove-all-cpv'></i>
                                                  </th>
                                              </tr>
                                              @foreach($cpvAttributes as $key => $cpvA)
                                              <tr data-key="true">
                                                  <td> <input type="text" name="view_id_{{ $key }}" value = "{{ $cpvA->view_id}}"></td>
                                                  <td> {{ $cpvA->cpv_code}}</td>
                                                  <td><input type="text" name="cpv_name_{{ $key }}" value ="{{ $cpvA->cpv_name}}"></td>
                                                  <td><input type="text" name="estimated_price_{{ $key }}" value ="{{ $cpvA->estimated_price}}"></td>
                                                  <td>
                                                      <input type="hidden" name='cpv_id_{{ $key }}' value="{{ $cpvA->cpv_id}}">
                                                      <input type='hidden' name='cpv_code_{{$key}}' value = {{ $cpvA->cpv_code}}>
                                                  </td>
                                              </tr>
                                              @endforeach
                                            </table>
                                        </div>
                                        <div class="col-md-6 mt-4">
                                            <div class="form-group">
                                                <label>Նախահաշվարկային գին</label>
                                                <input type="text" name="estimated"  class="form-control" style="margin-bottom: 5px;" value='{{ $tenderState->estimated }}'>
                                                <input type="file" name="estimated_file" style="padding-top: 3px;" class="form-control">
                                                @if(!empty($tenderState->estimated_file))
                                                <a href='{{$tenderState->estimated_file}}' target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-file"></i></a>
                                                @endif
                                                <p style="color:red;width: 100%;"> {{ $errors->first('estimated') }} </p>
                                                <p style="color:red;width: 100%;"> {{ $errors->first('estimated_file') }} </p>
                                            </div>
                                        </div>
                                        <div class = "col-md-12">
                                            <div class = "row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Հրավեր<span style="color:red;font-size:12px">*</span></label>
                                                        <input type="file" name="invitation_file" style="padding-top: 3px;" class="form-control">
                                                        @if (!empty($tenderState->invitation_link))
                                                        <a href='{{$tenderState->invitation_link}}' target="_blank" class="btn btn-xs btn-primary" download><i class="fa fa-file"></i></a>
                                                        @endif
                                                        <p style="color:red;width: 100%;"> {{ $errors->first('invitation_link') }} </p>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class = "col-md-12">
                                            <div class = "row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Ծածկագիր<span class="required-fields" style="color:red;font-size:12px">*</span></label>
                                                        <input type="text" name="passwordTender"  required="required" value='{{ $tenderState->password }}' class="form-control" style="margin-bottom: 5px;" >
                                                        <p style="color:red;width: 100%;"> {{ $errors->first('passwordTender') }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group save-tender-text-warning text-right" style="display:none;">
                                <h6>Ավելացված Cpv կոդերից առնվազն 1 հատը պետք է գրանցված լինի համակարգում</h6>
                            </div>
                            <div class="form-group">
                                <a href="/admin/tender_state"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                                <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0 save-tender-btn">Պահպանել</button>
                            </div>
                            {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6" style = "display: none;">
            <div class="card">
                <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active cpv-section" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Ավելացնել CPV</button>
                  </li>
{{--                   <li class="nav-item" role="presentation">
                    <button class="nav-link category-section" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Ավելացնել կատեգորիա</button>
                  </li> --}}
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active mt-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <a class="cpvCat btn btn-xs btn-primary mb-2" data-type="1" href="#">Ապրանքներ</a> 
                    <a class="cpvCat btn btn-xs btn-primary mb-2" data-type="2" href="#">Ծառայություններ</a> 
                    <a class="cpvCat btn btn-xs btn-primary mb-2" data-type="3" href="#">Աշխատանքներ</a> 
                    <br>
                    <input id="plugins4_q">
                    <h4 class="mt-0 header-title" id="cpvCatText">Ապրանքներ</h4>
                    <div id="tree">
                    </div>
                    <a class="cpv-append btn btn-xs btn-primary"  href="#">Ավելացնել</a> 
                  </div>
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <a class="categoryCat btn btn-xs btn-primary mb-2" data-type="6" href="#">Ապրանքներ</a> 
                    <a class="categoryCat btn btn-xs btn-primary mb-2" data-type="3" href="#">Ծառայություններ</a> 
                    <a class="categoryCat btn btn-xs btn-primary mb-2" data-type="38" href="#">Աշխատանքներ</a> 
                    <div id="cTree"></div>
                    <a class="category-append btn btn-xs btn-primary"  href="#">Ավելացնել</a> 
                  </div>
                </div>
                </div>
            <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@stop
@section('scripts')
<script src="/assets/back/plugins/treeview/jstree.min.js"></script>

<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>
<script>

@if($tenderState->type != " PAPER")
$(".estimatedPrice-section").show();
@else
$(".estimatedPrice-section").hide();
@endif

@if($tenderState->is_competition)
$(".is_not_competition").removeClass("active")
@else
$(".is_competition").removeClass("active")
@endif

    $('input[name=end_time]').mask('00:00');
    $('input[name=start_time]').mask('00:00');

    var td_type = $(".tp_name").val().trim();

    $(document).on("input", ".cpv-table .select2-search__field", function () {
    var cpv = $(this).val().trim();
    var checkCpvExist = false;
    $(".cpv-table").find('tr[data-key]').remove();
    $("#cpvSelect").empty();
    $(".view-id-inp").val(" ");
    $(".cpv-name-inp").val(" ");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       $.ajax({
          type: "POST",
          url: "/admin/get/cpv/by/text",
          dataType: "json",
          data: {cpv:cpv},
          success: function(data){
                    var notexistCpvsCount = $(".notexistCpvsCount").val();
                    var cpvsCount = $(".cpvsCount").val();
                    cpv = cpv.split(" ");
                    if(data.length != 0){
                        $(".cpv-table").show();
                    }
                    $(".cpvOrCategory").val("cpv");
                    for( index=0; index< data.length; index++){
                        if(data[index].id != undefined){
                                checkCpvExist = true;
                                $("#cpvSelect").append("<option selected value='"+data[index].id+"'>"+data[index].code+"</option>");
                                $(".cpv-table").append(`
                                <tr data-key=${index} data-cpv-name-inp=${index}>
                                    <td><input name='view_id_${index}' required='required' ></td>
                                    <td>${data[index].code}</td>
                                    <td><input name='cpv_name_${index}' required='required' ></td>
                                    <td><input name='unit_${index}'</td>
                                    <td><input name='count_${index}'</td>
                                    <td><input name='specification_${index}'</td>
                                    <td><input name='estimated_price_${index}'</td>
                                    <td>
                                        <button type="button" class="w-100 open-statistics-modal btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                            Վիճակագրություն <i class="ml-1 fa fa-chart-line"></i>
                                        </button>
                                    </td>
                                    <input type='hidden' name='cpv_code_${index}' value = '${data[index].code}'>
                                    <input type='hidden' name='cpv_id_${index}' value = '${data[index].id}'>
                                </tr>`);
                        }else{
                            $(".cpv-table").append(`
                            <tr class='not-exists' data-key=${index}>
                                <td><input name='view_id_ne_${notexistCpvsCount}'></td>
                                <td><input type='hidden' name='cpv_code_ne_${notexistCpvsCount}' value = '${data[index].code}'>${data[index].code}</td>
                                <td><input name='cpv_name_ne_${notexistCpvsCount}'</td>
                                <td><input name='estimated_price_ne_${notexistCpvsCount}'</td>
                                <td></td>
                            </tr>`);
                            notexistCpvsCount++
                        }
                        cpvsCount++;
                        $(".cpv-table textarea").removeAttr("disabled");
                        $(".notexistCpvsCount").val(notexistCpvsCount);
                        $(".cpvsCount").val(cpvsCount);
                    }
                if(!checkCpvExist){
                    $(".save-tender-btn").attr("disabled","disabled");
                    $(".save-tender-text-warning").show();
                }else{
                    $(".save-tender-btn").removeAttr("disabled");
                    $(".save-tender-text-warning").hide();
                }
          },
        });
    })

    $(document).on("click", ".remove-all-cpv", function () {
        $(".cpv-table").find('tr[data-key]').remove();
        $(".view-id-inp").val(" ");
        $(".cpv-name-inp").val(" ");
        $(".notexistCpvsCount").val(0);
        $(".cpvsCount").val(0);
        $(".select2-selection__choice").remove();
        $(".select2-results__options").empty();
        $("#cpvSelect").empty();
    })
    $(document).on("input", ".cpv-name-inp", function () {
        var regex = /("((?:""|[^"])*)"|(.*)\r?\n?|\r?)/g;
        var found = $(this).val().match(regex);
        var found = found.filter(function (el) {
            if(el.length != 1){
                return el ;
            }
        })
        for(var i = 0; i < found.length; i++){
            text = found[i];
            var row = $("tr[data-key="+i+"]");
            row.find("td:nth-child(3) input").val(text);
        }
    })

    $(document).on("input", ".view-id-inp", function () {
        var regex = /(.*)\r?\n?|\r?/g;
        var found = $(this).val().match(regex);
        for(var i = 0; i < found.length; i++){
            text = found[i];
            var row = $("tr[data-key="+i+"]");
            row.find("td:nth-child(1) input").val(text);
        }
    })

    $("#youtube_link").on("change", function () {
        $("#iframeYoutube").attr("src",$(this).val());
    });

    if( $("#edit-tender-kind").is(':checked') ){
        $("#tender-region").attr("disabled","disabled");
        $("#tender-region").find('option').attr("selected","");
    }

    $("#edit-tender-kind").change(function(){

        if( $('#edit-tender-kind').is(':checked')) {
            $("#tender-region").attr("disabled","disabled");
            $("#tender-region").find('option').attr("selected","");
        }else{
            $("#tender-region").removeAttr("disabled");
        }

    })
    // $(".dataTime").bootstrapMaterialDatePicker({format:"YYYY-MM-DD HH:mm:ss"});

    $('.endDataTime').bootstrapMaterialDatePicker({
     weekStart: 0, format: 'YYYY-MM-DD HH:mm:ss'
    });

    $('.startDataTime').bootstrapMaterialDatePicker({
        weekStart: 0, format: 'YYYY-MM-DD HH:mm:ss', shortTime : true
    }).on('change', function(e, date) {
        if(date === undefined){
            date =   $('.startDataTime').bootstrapMaterialDatePicker().val();
        }
        $('.endDataTime').bootstrapMaterialDatePicker('setMinDate', date);
    });
    $('.startDataTime').change();

    // $('#tree').jstree({
    //         "plugins" : [ "search" ,"checkbox"],
    //         "checkbox" : { "three_state": false, "keep_selected_style" : false },
    //         'core' : {
    //             data : function (node, cb) {
    //                 $.ajax({ url : "/admin/cpv/tree/json/1","dataType" : "json" }).done(function (data) {
    //                     x = [];
    //                     children = [];

    //                     $.each( data, function( i, val ) {
    //                         children = getJson(val.children);
    //                         x[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
    //                     })
    //                     cb(x);
    //                 });
    //             }
    //         }
    // });


	$(".append").on('click',function(event) {
        checked  = $("#tree").jstree("get_checked",true);

        for (let index = 0; index < checked.length; index++) {
            $("#cpvSelect").append("<option selected value='"+checked[index].id+"'>"+checked[index].text+"</option>");
        }

    })


	$(".cpvCat").on('click',function(event) {
		event.preventDefault();
        /* Act on the event */

        type = $(this).data("type");
        $("#cpvCatText").text($(this).text());

        $('#tree').jstree(true).settings.core.data = function (node, cb) {
                    $.ajax({ url : "/admin/cpv/tree/json/"+type,"dataType" : "json" }).done(function (data) {
                        x = [];
                        children = [];

                        $.each( data, function( i, val ) {
                            children = getJsonCategory(val.children);
                            x[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
                        })
                        cb(x);
                    });
                };
        $('#tree').jstree(true).refresh();

    });

    function getJsonCategory(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" :val.name, "children":children };
        })
        return t;
    }

    function submitMe(){
       $("#tree").jstree("get_checked",null,true);
    }

    var to = false;
    $('#plugins4_q').change(function () {
        if(to) { clearTimeout(to); }
        to = setTimeout(function () {
        var v = $('#plugins4_q').val();
        $('#tree').jstree(true).search(v);
        }, 250);
    });

    function getJson(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
        })
        return t;
    }

    // $('#cTree').jstree({
    //         "plugins" : [ "search" ,"checkbox"],
    //         "checkbox" : { "three_state": false, "keep_selected_style" : false },
    //         'core' : {
    //             data : function (node, cb) {
    //                 $.ajax({ url : "/admin/categories/tree/json/6","dataType" : "json" }).done(function (data) {
    //                     x = [];
    //                     children = [];

    //                     $.each( data, function( i, val ) {
    //                         children = getJsonCategory(val.children);
    //                         x[i] = { "id" : val.id, "text" :val.name, "children":children };
    //                     })
    //                     cb(x);
    //                 });
    //             }
    //         }
    // });

    $(".cpv-append").on('click',function(event) {
        checked  = $("#tree").jstree("get_checked",true);
        $(".cpvOrCategory").val("cpv");
        for (let index = 0; index < checked.length; index++) {
            $("#cpvSelect").append("<option selected value='"+checked[index].id+"'>"+checked[index].text+"</option>");
        }

    })  

    $(".category-append").on('click',function(event) {
        $(".cpvOrCategory").val("category");
        checked  = $("#cTree").jstree("get_checked",true);

        for (let index = 0; index < checked.length; index++) {
            $("#cpvSelect").append("<option selected value='"+checked[index].id+"'>"+checked[index].text+"</option>");
        }

    })

    $(".cpv-section").click( function (){
        $(".CPV-or-Category").text("CPV ");
        $("#cpvSelect").empty();
    })    

    $(".category-section").click( function (){
        $(".CPV-or-Category").text("կատեգորիա");
        $("#cpvSelect").empty();
    })

    $(".categoryCat").on('click',function(event) {
        event.preventDefault();
        /* Act on the event */
        $("#cpvCatText").text($(this).text());
        type = $(this).data("type");

        $('#cTree').jstree(true).settings.core.data = function (node, cb) {
                    $.ajax({ url : "/admin/categories/tree/json/"+type,"dataType" : "json" }).done(function (data) {
                        x = [];
                        children = [];

                        $.each( data, function( i, val ) {
                            children = getJsonCategory(val.children);
                            x[i] = { "id" : val.id, "text" : val.name, "children":children };
                        })
                        cb(x);
                    });
                };
        $('#cTree').jstree(true).refresh();

    });

    function getJsonCategory(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" :val.name, "children":children };
        })
        return t;
    }
</script>
@stop
