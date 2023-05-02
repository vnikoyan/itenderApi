
@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Տեղեկատվություն @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Տեղեկատվություն  @stop

@section('content')
    <style>
    .list-group{
        display: none
    }
    .cursor-pointer{
        cursor: pointer;
        font-size: 12px;
    }
    .select2-selection__rendered{
        width: auto !important;
        overflow: hidden !important;
    }
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
    .cpv-table tr{
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
    .dropify-wrapper{
            width: 100%!important;
    }
    .estimatedPrice-section{
        display: none;
    }
    </style>
    <link href="/assets/back/plugins/treeview/themes/default/style.css" rel="stylesheet">

    <link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
    <!-- end page title end breadcrumb -->
    <div class="w-100">
        <div class="col-lg-8s">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                        {{ Form::model($parserState,array('route' => array('tender_state_parser.update',$parserState->id ),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                        @method('PUT')
                        <input type="hidden"  name="previous"  value='{{ (\Input::old("previous") ? Input::old("previous") :  url()->previous()) }}  '>
                        <input type="hidden" name="cpvOrCategory" value="" class= "cpvOrCategory">
                        <input type="hidden" name="type_name" value="{{ $parserState->type_name }}">
                        <input type="hidden" name="model" value="{{ $parserState->type_name }}">
                        <div class="form-group row">
                            <div class="col-md-12 organizer-section">
                                    <label for="" class="">Պատվիրատու<span style="color:red;font-size:12px">*</span></label>
                                    <select class="select2 form-control organizer-sel" required="required" name="organizator">
                                        <option value="">-- Ընտրել --</option>
                                        @foreach($organizator as $val)
                                            <option {{ (\Input::old("ministry") == $val->id ? "selected":"") }} value="{{ $val->id.','.$val->is_state}}" data-key="{{ $val->id }}" >{{ $val->name}}</option>
                                        @endforeach
                                    </select>
                                    <p style="color:red;width: 100%;"> {{ $errors->first('organizator') }} </p>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <input type="hidden" name="inovation_link" value="{{ $parserState->link }}">
                                    @foreach($language as $key => $value)
                                        <div class="col-md-12">
                                            <label for="" class="">Անվանում {{ $value->name }}</label>
                                            {{ Form::text('title['.$value->code.']', $value->title, ["class"=>"form-control","placeholder"=>"Անվանում ".$value->name  ])}}
                                            <p style="color:red;width: 100%;"> {{ $errors->first('title.'.$value->code) }} </p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="" class="">Սկիզբ<span style="color:red;font-size:12px">*</span></label>
                                        {{ Form::dateTime('start_date', date('Y-m-d H:i',strtotime($parserState->start_date)), ["required"=>"required","class"=>"form-control startDataTime","placeholder"=>"Սկիզբ"])}}
                                        <p style="color:red;width: 100%;"> {{ $errors->first('start_date') }} </p>
                                    </div> 
                                    <div class="col-md-6">
                                        <label for="" class="">Ավարտ<span style="color:red;font-size:12px">*</span></label>
                                        {{ Form::dateTime('end_date', date('Y-m-d H:i',strtotime($parserState->end_date)), ["required"=>"required","class"=>"form-control endDataTime","placeholder"=>"Ավարտ"])}}
                                        <p style="color:red;width: 100%;"> {{ $errors->first('end_date') }} </p>
                                    </div> 
                                </div>
                                <div class="col-md-12 searchTenderState-section">
                                    <div class="form-group row">    
                                        <label>Մրցույթի որոնում</label>
                                        <input type="text" class="form-control searchTenderState" style="margin-bottom: 5px;" >
                                        <p style="color:red;width: 100%;"> {{ $errors->first('searchTenderState') }} </p>
                                        <select class="form-control searchTenderState-sel" style="display: none;" name="searchTenderState"></select>
                                    </div>
                                </div>
                                <div class = "row mb-4">
                                    <div class="col-md-2 mt-2">
                                        <input type="hidden" name="is_competition" id="is_competition" value="{{ $parserState->is_competition == 1  ? "1" : null }}"> 
                                        @if($parserState->is_competition == 1)
                                            <p>Մրցույթ է</p>
                                        @else
                                            <p>Հայտարարություն է</p>
                                        @endif
                                    </div>   
                                    <div class="col-md-2 mt-2" style="display:none;">
                                        <input type="checkbox" name="is_new" id="is_new" {{ (\Input::old("is_new")  ? "checked":"") }} value="1"> <label for="is_new" class=""> Նոր հրավեր</label>
                                    </div>
                                    <div class="col-md-2 mt-2" style="display:none;">
                                    <input type="checkbox" class="beneficiari" name="beneficiari" value="1"> <label  for=""> Նոր իրական շահառուներ</label>
                                    </div>
                                    <div class="col-md-2 mt-2" style = "display: none;">
                                        <input type="checkbox" class="is_with_model" name="is_with_model" value="1"> <label  for=""> Մոդել</label>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <input type="checkbox" name="is_closed" value="1"> 
                                        <label for="is_competition" class=""> Բաց է</label>
                                    </div>
                                    <div class="col-md- mt-2">
                                        <input type="checkbox" name="kind" id="parser-edit-kind" value=""> <label for="parser-edit-kind" > Միջազգային </label>
                                    </div> 
                                </div>
                                <div class= "row">
                                    <div class="col-md-3" >
                                        <label for="" class="">Մարզեր<span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                        <select class=" form-control" required="required"  name="regions" id="tender-edit-kind">
                                            <option value="">-- Ընտրել --</option>
                                            @foreach($regions as $val)
                                                <option {{ (\Input::old("regions") == $val->id ? "selected":"") }} value="{{ $val->id}}">{{ $val->name}}</option>
                                            @endforeach
                                        </select>
                                        <p style="color:red;width: 100%;"> {{ $errors->first('regions') }} </p>
                                    </div> 
                                    <div class="col-md-3">
                                        <label for="" class="" >էլեկտրոնային թղթային<span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                        <select class=" form-control tender-type"  required="required"  name="type">
                                            <option value="">-- Ընտրել --</option>
                                            @if($parserState->type_name == "ELAH")
                                                <option value="ELECTRONIC">Էլեկտրոնային (armeps)</option>
                                                <option value="PAPER">Թղթային</option>
                                                <option value="ELECTRONIC AUCTION" selected>Էլեկտրոնային աճուրդ</option>
                                                
                                                @else
                                                <option {{ (\Input::old("type") == "ELECTRONIC" ? "selected":"") }} value="ELECTRONIC">Էլեկտրոնային (armeps)</option>
                                                <option {{ (\Input::old("type") == "PAPER" ? "selected":"") }} value="PAPER">Թղթային</option>
                                                <option {{ (\Input::old("type") == "ELECTRONIC AUCTION" ? "selected":"") }} value="ELECTRONIC AUCTION">Էլեկտրոնային աճուրդ</option>
                                            @endif
                                        </select>                                                            
                                        <p style="color:red;width: 100%;"> {{ $errors->first('type') }} </p>
                                    </div> 
                                    <div class="col-md-6">
                                        <label for="" class="">երաշխիքով-առանց երաշխիք<span style="color:red;font-size:12px">*</span></label>
                                        <select class=" form-control" required="required" name="guaranteed">
                                            <option value="">-- Ընտրել --</option>
                                            <option  value="1">Երաշխիքով</option>
                                            <option  value="0">Առանց երաշխիքի</option>
                                        </select>
                                        <p style="color:red;width: 100%;"> {{ $errors->first('guaranteed') }} </p>
                                    </div> 
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="cpv-table w-100" style="table-layout: fixed;">
                                            <tr>
                                                <th>Չափաբաժնի համարը</th>
                                                <th style="width: 400px;">
                                                    CPV<span style="color:red;font-size:12px">*  
                                                </th>
                                                <th>
                                                    Գնման առարկայի անվանումը
                                                </th>
                                                <th>
                                                    Չափման միավոր
                                                </th>
                                                <th>
                                                    Քանակ
                                                </th>
                                                <th>
                                                    Տեխնիկական բնութագիր
                                                </th>
                                                <th>
                                                    Նախահաշվարկային գին
                                                </th>
                                                <th>
                                                    Գործողություն
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <textarea class="view-id-inp" type = "text" disabled></textarea>
                                                </th>
                                                <th>
                                                    <select class="select2 form-control" id="cpvSelect" required="required" name="cpv[]" multiple>
                                                    </select>
                                                    <p style="color:red;width: 100%;"> {{ $errors->first('cpv') }} </p>
                                                </th>
                                                <th>
                                                    <textarea class="cpv-name-inp" type="text" disabled></textarea>
                                                </th>
                                                <th>
                                                    <textarea class="cpv-unit-inp" type="text"></textarea>
                                                </th>
                                                <th>
                                                    <textarea class="cpv-count-inp" type="text"></textarea>
                                                </th>
                                                <th>
                                                    <textarea class="cpv-specification-inp" type="text"></textarea>
                                                </th>
                                                <th>
                                                    <textarea class="estimated-price-inp" type="text"></textarea>
                                                </th>
                                                <th>
                                                    <div class="btn btn-danger remove-all-cpv">
                                                        <i class='fa fa-ban'></i>
                                                    </div>
                                                </th>
                                            </tr>
                                        </table>
                                        <input type="hidden" name="notexistCpvsCount" class="notexistCpvsCount" value="0">
                                        <input type="hidden" name="cpvsCount" class="cpvsCount" value='0'>
                                    </div> 
                                    <div class="col-md-12 estimatedPrice-section mt-2 mb-2">
                                        <div class="form-group">    
                                            <label>Ավելացնել նախահաշվային գները</label>
                                            <input type="file" class="form-control estimatedPrice" style="margin-bottom: 5px;" name="estimatedPrice">
                                            <p style="color:red;width: 100%;"> {{ $errors->first('estimatedPrice') }} </p>
                                        </div>
                                    </div>  
                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                            <label>Ընթացակարգ <span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                            <select name="procedure" required="required"  class="form-control" >
                                                <option value="">-- Ընտրել --</option>
                                                @foreach($procedure as $val)
                                                    @if($parserState->type_name == "ELAH")
                                                        @if($val->contact == "ԷԱՃ")
                                                            <option value="{{ $val->id}}" selected>{{$val->contact}}</option>
                                                        @else
                                                            <option value="{{ $val->id}}">{{$val->contact}}</option>
                                                        @endif
                                                    @else
                                                    <option value="{{ $val->id}}">{{$val->contact}}</option>
                                                    @endif
                                                    @endforeach
                                            </select>
                                            <p style="color:red;width: 100%;"> {{ $errors->first('procedure') }} </p>
                                        </div>
                                    </div>  
                                    <div class = "col-md-12">
                                        <div class ="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Նախահաշվարկային գին</label>
                                                    <input type="text" name="estimated"  class="form-control" style="margin-bottom: 5px;" value='{{ \Input::old("estimated") }}'>
                                                    <input type="file" name="estimated_file" style="padding-top: 3px;" class="form-control">
                                                    <p style="color:red;width: 100%;"> {{ $errors->first('estimated') }} </p>
                                                    <p style="color:red;width: 100%;"> {{ $errors->first('estimated_file') }} </p>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Հրավեր<span style="color:red;font-size:12px">*</span></label>
                                                    <input type="file" name="invitation_file" style="padding-top: 3px;" class="form-control">
                                                    <a href='{{$parserState->link }}' target="_blank" class="btn btn-xs btn-primary" download><i class="fa fa-file"></i></a>
                                                    <p style="color:red;width: 100%;"> {{ $errors->first('invitation_link') }} </p>
                                                    <div class="addition-buttons"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if($parserState->type_name == "ELAH")
                                            <label>Ծածկագիր <span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                            <input type="text" name="passwordTender" required="required"  value='{{ $parserState->password }}' class="form-control" style="margin-bottom: 5px;" >
                                            <p style="color:red;width: 100%;"> {{ $errors->first('passwordTender') }} </p>
                                            @else
                                            <label>Ծածկագիր <span  class="required-fields" style="color:red;font-size:12px">*</span></label>
                                            <input type="text" name="passwordTender" required="required"  value='{{ \Input::old("passwordTender") }}' class="form-control" style="margin-bottom: 5px;" >
                                            <p style="color:red;width: 100%;"> {{ $errors->first('passwordTender') }} </p>
                                            @endif    

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group save-tender-text-warning text-right" style="display:none;">
                            <h6>Ավելացված Cpv կոդերից առնվազն 1 հատը պետք է գրանցված լինի համակարգում</h6>
                        </div>
                        <div class="form-group">
                            <a href="{{ url()->previous() }}"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                            <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0 save-tender-btn">Պահպանել</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 50% !important">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ուղարկել դեպի վիճակագրություն</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="selected_cpv" class="form-control">
                        <input type="hidden" id="specification_id" class="form-control">
                        <input type="hidden" id="selected_tender_cpv" class="form-control">
                        <input type="hidden" id="selected_cpv_statistics" class="form-control">
                        <input type="hidden" id="specifications" class="form-control">
                        <div>
                            <div class="mt-2">
                                <label for="specification">Տեխնիկական բնութագիր*</label>
                                <textarea id="specification" class="form-control"></textarea>
                            </div>
                            <div class="specification-list-block mt-1" style="display: none">
                                <label>Նմանատիպ տեխնիկական բնութագրեր</label>
                                <table class="table">
                                    <tbody id="specification-list"></tbody>                                                            
                                </table>
                            </div>
                            <div class="mt-2">
                                <label for="estimated_price">Նախահաշվային գին</label>
                                <input type="number" id="estimated_price" class="form-control">
                            </div>
                            <div class="mt-2">
                                <label>Մարզեր*</label>
                                <select class="form-control" required="required" name="regions" id="region">
                                    <option disabled >-- Ընտրել --</option>
                                    @foreach($regions as $val)
                                        <option {{ (\Input::old("regions") == $val->id ? "selected":"") }} value="{{ $val->id}}">{{ $val->name}}</option>
                                    @endforeach
                                </select>                                                            
                                <p style="color:red;width: 100%;"> {{ $errors->first('regions') }} </p>
                            </div>
                            <div class="mt-2">
                                <label for="unitOfMeasurement:">Չափման միավոր*</label>
                                <select class="form-control" required="required" name="unit" id="unit">
                                    <option>-- Ընտրել --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{$unit->title}}</option>
                                    @endforeach
                                </select>  
                            </div>
                            <div class="mt-2">
                                <label for="count">Գնման առարկայի քանակը*</label>
                                <input type="text" id="count" class="form-control">
                            </div>
                            {{-- <div class="mt-2">
                                <label for="estimated_price">Գնումների պլաններով տվյալ գնման առարկայի գումարների հանրագումարը</label>
                                <input type="number" id="estimated_price" class="form-control">
                            </div> --}}
                            <div class="mt-2">
                                <label for="winner_get_date">Հաղթողին որոշելու ամսաթիվը*</label>
                                <input type="date" id="winner_get_date" class="form-control winner_get_date">
                            </div>
                            <div class="mt-4">
                                <div class="col-2 d-flex justify-content-between align-items-center">
                                    <label for="established" checked class="mb-0 mr-1">կայացած</label>
                                    <input checked type="radio" id="established" name="variant" value="1">
                                </div>
                                <div class="col-2 d-flex justify-content-between mt-2 align-items-center">
                                    <label for="failed" class="mb-0 mr-1">չկայացած</label>
                                    <input type="radio" id="failed" name="variant" value="0">
                                </div>
                            </div>
                            <div class="mt-4">
                                <select name="failed_substantiation" id="failedVariants" class="form-control">
                                    <option value="not_match_conditions">հայտերից ոչ մեկը չի համապատասխանում հրավերի պայմաններին</option>
                                    <option value="not_requirement_purchase">դադարում է գոյություն ունենալ գնման պահանջը</option>
                                    <option value="no_submitted_application">ոչ մի հայտ չի ներկայացվել</option>
                                    <option value="no_contract_signed">պայմանագիր չի կնքվում</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <h6>Մասնակիցներ</h6>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Հաղթող</th>
                                    <th class="text-center">Անուն</th>
                                    <th class="text-center">Գին</th>
                                    <th class="text-center">
                                        <div class="row">
                                            <div class="col row justify-content-end">
                                                ԱՀՀ
                                            </div>
                                            <div class="col row justify-content-start">
                                                <input class="ml-1 col form-check-input" type="checkbox" id="vat-checkbox">
                                            </div>
                                        </div>
                                        
                                    </th>
                                    <th class="text-center">Ընդհանուր գին</th>
                                </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                            <button class="btn btn-md btn-success d-flex align-items-center justify-content-center" id="addBtn" type="button">
                                <i class="fas fa-plus-circle" style="font-size: 16px"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Փակել</button>
                        <button type="button" class="saveCpvStatistics btn btn-primary">Պահպանել</button>
                    </div>
                </div>
            </div>
        </div>
        <div style="z-index: 1060; background: rgba(0, 0, 0, 0.3)" class="modal fade" id="showFullSpecificationModalCenter" tabindex="-1" role="dialog" aria-labelledby="showFullSpecificationModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> Առկա տեխնիկական բնութագիրը </p>
                        <p id="new-specification" class="fullSpecificationBlock"></p>
                        <hr>
                        <p> Մուտքագրված տեխնիկական բնութագիրը </p>
                        <p id="old-specification" class="currentSpecificationBlock"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6" style ="display: none;">
            <div class="card">
                <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active cpv-section" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Ավելացնել CPV</button>
                  </li>
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

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

@if($parserState->type_name == "ELAH")
    <script>
        var organizator = "{{ trim($parserState->customer_name) }}";
        $(".organizer-sel option").each(function( index ) {
            if($(this).text().trim() == organizator){
                $(this).attr("selected", "selected");
                $(".organizer-section .select2-selection__rendered").attr('title',$('.organizer-sel :selected').text()).text($('.organizer-sel :selected').text());
            }
        });
    </script>
@endif

@if( trim($parserState->type_name) == "TIGKKGMH")
<script>    
        $("input[name='passwordTender']").removeAttr("required");
        $("input[name='passwordTender']").parents().find(".required-fields").hide();
</script>
@endif
<script>

    var tender_type = $(".tender-type").val();
    if(tender_type != "PAPER"){
            $(".estimatedPrice-section").show();
        }else{
            $(".estimatedPrice-section").hide();
        }

    $(".tender-type").change(function(){
        if($(this).val() != "PAPER"){
            $(".estimatedPrice-section").show();
        }else{
            $(".estimatedPrice-section").hide();
        }
    }) 

    $(".estimatedPrice").change(function(){
        $(".estimatedPrice-section").find('p').text("").hide();
        var formData = new FormData();
        var cpvs = $("#cpvSelect").val();
        formData.append('estimatedPrice', $('.estimatedPrice')[0].files[0]);
        formData.append('cpvs', cpvs);
        var cpvCodes = [];
        $(".cpv-table tr ").each(function( index ) {
            if($(this).find('td')[1]){
                var cpv_code = $(this).find('td')[1].innerHTML.trim();
                cpvCodes.push(cpv_code)
            }
        });
        formData.append('cpvCodes', JSON.stringify(cpvCodes));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url : 'https://api.itender.am/admin/add/estimated/price',
            type : 'POST',
            data : formData,
            processData: false,
            contentType: false,
            success : function(data) {
                if(data.error){
                    $(".estimatedPrice-section").find('p').text(data.message).show();
                }else{
                    $(".cpv-table tr ").each(function( index ) {
                        if($(this).find('td')[1]){
                            var cpv_code = $(this).find('td')[1].innerHTML.trim();
                            if(data[cpv_code]){
                                $(this).find('td')[6].firstChild.value = data[cpv_code]['price'];
                            }
                        }
                    });
                    }
                }
            });
        })
    
        if($("#is_competition").val() == 1){
            $(".searchTenderState-section").hide();
            $("#is_new").parent().show();
            $("#is_new").attr('checked','checked');
            $(".beneficiari").parent().show();
            $(".is_with_model").parent().show();
            $(".beneficiari").attr('checked','checked');
            $(".is_with_model").attr('checked','checked');
        }
        $(".organizer-sel").change( function(){
        var val = $(this).val().split(',');
        if(val[1] == 2){
             $("input[name='passwordTender']").removeAttr("required");
             $("select[name='procedure']").removeAttr("required");
             $("select[name='regions']").removeAttr("required");
             $("select[name='type']").removeAttr("required");
             $(".required-fields").hide();
        }else{
             $("input[name='passwordTender']").attr("required","required");
             $("select[name='procedure']").attr("required","required");
             $("select[name='regions']").attr("required","required");
             $("select[name='type']").attr("required","required");
             $(".required-fields").show();
        }
    })

    let cpvs = [];

    $(".searchTenderState-sel").change(function(){
        $("input[name='is_competition']").prop('checked', false);
        $("#is_competition").attr("disabled",true);
        var organizator = $(this).find(":selected").attr("data-organizator");
        var password = $(this).find(":selected").attr("data-password");
        var name = $(this).find(":selected").attr("data-name");
        var cpv = $(this).find(":selected").attr("data-cpv");
        var kind = $(this).find(":selected").attr("data-kind");
        var is_new = $(this).find(":selected").attr("data-is_new");
        var is_closed = $(this).find(":selected").attr("data-is_closed");
        var invitation_file = $(this).find(":selected").attr("data-invitation_file");
        var procedure = $(this).find(":selected").attr("data-procedure");
        var regions = $(this).find(":selected").attr("data-regions");
        var type = $(this).find(":selected").attr("data-type");
        var guaranteed = $(this).find(":selected").attr("data-guaranteed");
        var estimated = $(this).find(":selected").attr("data-estimated");
        var estimated_file = $(this).find(":selected").attr("data-estimated_file");
        var estimated_price = $(this).find(":selected").attr("data-estimated_price");
        const esimatedFile = estimated_file !== 'null' ? estimated_file : estimated_price !== 'null' ? estimated_price : false
        if(invitation_file){
            $('.addition-buttons').append(
                `<a href="${invitation_file}" target="_blank" class="btn btn-xs btn-primary mr-2" download>
                    <i class="fa fa-file"></i> Հրավեր
                </a>`
            );
        }
        if(esimatedFile){
            $('.addition-buttons').append(
                `<a href="${esimatedFile}" target="_blank" class="btn btn-xs btn-primary" download>
                    <i class="fa fa-file"></i> Գնումների պլանը
                </a>`
            );
        }
        $("#cpvSelect").removeAttr('required');
        $("input[name='invitation_file']").removeAttr('required');
        $("input[name='end_date']").removeAttr('disabled');

        $("#cpvSelect").attr("disabled",true);
        $("input[name='title[hy]']").val(JSON.parse(name).hy);
        $("input[name='title[ru]']").val(JSON.parse(name).ru);
        $("input[name='title[en]']").val(JSON.parse(name).en);
        $('.organizer-sel option').removeAttr("selected");
        $('.organizer-sel option[data-key="'+organizator+'"]').attr("selected", "selected");
        $(".select2-selection__rendered").attr('title',$('.organizer-sel :selected').text()).text($('.organizer-sel :selected').text());
        $("select[name='regions']").val(regions);
        $("select[name='guaranteed']").val(guaranteed);
        $("select[name='procedure']").val(procedure);
        $("input[name='passwordTender']").val(password);
        if( kind == "international"){
             $("input[name='kind']").attr('checked', 'checked');
        }
        if(type != ""){
            $("select[name='type']").val(type);
        }
        if(estimated != "null"){
            $("input[name='estimated']").val(estimated);

        }
        if(is_new != "null"){
             $("input[name='is_new']").attr('checked', 'checked');
        }
        if(is_closed != "0"){
             $("input[name='is_closed']").attr('checked', 'checked');
        }


        var val = $(".organizer-sel").find(":selected").val().split(',');
        if(val[1] == 2){
             $("input[name='passwordTender']").removeAttr("required");
             $("select[name='procedure']").removeAttr("required");
             $("select[name='regions']").removeAttr("required");
             $("select[name='type']").removeAttr("required");
             $(".required-fields").hide();
        }else{
             $("input[name='passwordTender']").attr("required","required");
             $("select[name='procedure']").attr("required","required");
             $("select[name='regions']").attr("required","required");
             $("select[name='type']").attr("required","required");
             $(".required-fields").show();
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        getSelectedTenderCpvs()
    })

    function getSelectedTenderCpvs() {
        $.ajax({
          type: "POST",
          url: "/admin/get/cpv/by/tender_state/id",
          dataType: "json",
          data: {id:$(".searchTenderState-sel").find(":selected").val()},
          success: function(data){
                $("#cpvSelect").empty();
                $(".cpv-table").find('tr[data-key]').remove();
                const typeName = $("input[name='type_name']").val();
                cpvs = [...data]
                for(index = 0; index < data.length; index++){
                    var cpv_code  = (data[index].cpv_code == null) ? data[index].code : data[index].cpv_code;
                    var estimated_price = (data[index].estimated_price == null) ? "" : data[index].estimated_price;
                    let btnClass = 'btn-primary';
                    const statistics = data[index].statistics
                    if(statistics){
                        if(statistics.participants && statistics.participants.length){
                            btnClass = 'btn-success';
                        } else {
                            btnClass = 'btn-warning';
                        }
                    }
                    if(data[index].cpv_id != 0){
                        $("#cpvSelect").append("<option selected value='"+data[index].id+"'>"+data[index].code+"</option>");
                        $(".cpv-table").append(`
                            <tr data-key=${index} data-id=${data[index].id}>
                                <td>
                                    <input value='${data[index].view_id}' disabled>
                                </td>
                                <td>${cpv_code}</td>
                                <td><input value='${data[index].cpv_name}'' disabled></td>
                                <td><input class="unit" type='text' value='${data[index].unit}'' ></td>
                                <td><input class="count" type='text' value='${data[index].count}'' ></td>
                                <td><input class="specification" type='text' value='${data[index].specification}'' ></td>
                                <td><input class="estimated-price" type='text' value='${estimated_price}' disabled></td>
                                <td>
                                    ${
                                        typeName === 'HGNA' || typeName === 'HBNA' ? 
                                        `<button type="button" class="w-100 open-statistics-modal btn ${btnClass}" data-toggle="modal" data-target="#exampleModalCenter">
                                            Վիճակագրություն <i class="ml-1 fa fa-chart-line"></i>
                                        </button>`
                                        :
                                        ''
                                    }
                                </td>
                            </tr>
                        `);
                    }else{
                        $(".cpv-table").append("<tr  class= 'not-exists' data-key='cpvs'><td><input value='"+data[index].view_id+"' disabled></td><td>"+cpv_code+"</td><td><input value='"+data[index].cpv_name+"'' disabled ></td><td><input type='text' value='"+estimated_price+"' disabled></td><td></td></tr>");
                    }
                }
            },
        });
    }
    
// ՀՀՎԱ-ԷԱՃԱՊՁԲ-22/9

    function editDistance(s1, s2) {
        s1 = s1.toLowerCase();
        s2 = s2.toLowerCase();

        var costs = new Array();
        for (var i = 0; i <= s1.length; i++) {
            var lastValue = i;
            for (var j = 0; j <= s2.length; j++) {
            if (i == 0)
                costs[j] = j;
            else {
                if (j > 0) {
                var newValue = costs[j - 1];
                if (s1.charAt(i - 1) != s2.charAt(j - 1))
                    newValue = Math.min(Math.min(newValue, lastValue),
                    costs[j]) + 1;
                costs[j - 1] = lastValue;
                lastValue = newValue;
                }
            }
            }
            if (i > 0)
            costs[s2.length] = lastValue;
        }
        return costs[s2.length];
        }

    function similitude(s1, s2) {
        var longer = s1;
        var shorter = s2;
        if (s1.length < s2.length) {
            longer = s2;
            shorter = s1;
        }
        var longerLength = longer.length;
        if (longerLength == 0) {
            return 1.0;
        }
        return (longerLength - editDistance(longer, shorter)) / parseFloat(longerLength);
    }

    $(document).on("change", ".specification-item", function () {
        $('#specification_id').val($(this).val());
    })

    $('#specification').keyup(function() {
        setTimeout(() => {
        const tenderCpvId = $('#selected_tender_cpv').val();
        const currentCpv = cpvs.find(item => +item.id === +tenderCpvId)
        const specificationsJson = $('#specifications').val();
        if(specificationsJson){
            const specifications = JSON.parse(specificationsJson);
            $('#specification-list').html('');
            if(specifications.length){
                let specificationsFormated = specifications;
                specificationsFormated.forEach((specification) => {
                    const description = specification.description.hy;
                    specification.percentage = Math.round(similitude($(this).val(), description) * 100)
                })
                specificationsFormated.sort((a, b) => b.percentage - a.percentage);
                specificationsFormated.splice(5, specificationsFormated.length - 5)
                specificationsFormated.forEach((specification) => {
                    const description = specification.description.hy
                    const percentage = Math.round(similitude($(this).val(), description) * 100)
                    const option = `
                            <tr>
                                <td>
                                    <input
                                        type="radio"
                                        class="specification-item"
                                        name="specification-item"
                                        ${+specification.id === +statisticsData.specification_id && 'checked'}
                                        value="${specification.id}"
                                    >
                                </td>
                                <td>${percentage}%</td>
                                <td>
                                    <span style="max-width: 350px;" class="d-inline-block text-truncate">
                                        ${description}
                                    </span>
                                </td>
                                <td>
                                    <button data-id="${specification.id}" data-toggle="modal" data-target="#showFullSpecificationModalCenter" type="button" class="show-full-specification btn btn-light">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `
                    $('#specification-list').append(option);
                })
            }
    
            $('.specification-list-block').css('display', 'block');
        }
        }, 1000);
    });


    $(document).on("click", ".show-full-specification", function () {
        const tenderCpvId = $('#selected_tender_cpv').val();
        const currentCpv = cpvs.find(item => +item.id === +tenderCpvId)
        const specificationId = $(this).attr('data-id')
        const specificationsJson = $('#specifications').val();
        const specifications = JSON.parse(specificationsJson);
        const currentSpecification = specifications.find(item => +item.id === +specificationId)
        const description = currentSpecification.description.hy
        $('.fullSpecificationBlock').html(description)
        $('.currentSpecificationBlock').html($('#specification').val())
        // highlight($('.currentSpecificationBlock'), $('.fullSpecificationBlock'));
        highlight($('.fullSpecificationBlock'), $('.currentSpecificationBlock'), 'old');
        highlight($('.fullSpecificationBlock'), $('.currentSpecificationBlock'), 'new');
    })

    $("#showFullSpecificationModalCenter").on("hidden.bs.modal", function () {
        $('.fullSpecificationBlock').html('')
        $('.currentSpecificationBlock').html('')
        // $("#showFullSpecificationModalCenter").css('', 'rgba(255, 99, 71, 0.2)')
    });

    // function highlight(newElem, oldElem){ 
    //     newElem.html(newElem.text().split('').map(function(val, i){
    //         return val != oldElem.text().charAt(i) ?
    //         "<span class='highlight'>"+val+"</span>" : 
    //         val;            
    //     }).join('')); 
    // }

    function highlight(newElem, oldElem){ 
        newElem.html(newElem.text().split('').map(function(val, i){
            return val != oldElem.text().charAt(i) ?
                "<span class='highlight-new'>"+val+"</span>" : 
            val;            
        }).join(''));
    }

    $(document).on('input', '.vat-input', function () {
        const vat = $(this).val();
        const index = $(this).parents('tr').attr('data-index');
        const value = $(`#value_${index}`).val();
        $(`#total_${index}`).val(+value + +vat);
    });

    $(document).on('input', '.price-input', function () {
        const value = $(this).val();
        const index = $(this).parents('tr').attr('data-index');
        const isWithVat = $('#vat-checkbox').is(":checked");
        if(isWithVat){
            $(`#vat_${index}`).val(value * 0.2);
            $(`#total_${index}`).val((value * 1.2));
        } else {
            $(`#vat_${index}`).val(0);
            $(`#total_${index}`).val((value));
        }
    });

    $(document).on('input', '#vat-checkbox', function () {
        const isWithVat = $(this).is(":checked");
        $('#tbody tr').each(function (i, element) {
            const index = $(this).attr('data-index');
            const value = $(`#value_${index}`).val();
            if(isWithVat){
                $(`#vat_${index}`).val(value * 0.2);
                $(`#total_${index}`).val((value * 1.2));
            } else {
                $(`#vat_${index}`).val(0);
                $(`#total_${index}`).val((value));
            }
        });
    });

    
    // function highlight(newElem, oldElem, type){ 
    //     var oldText = oldElem.text(),     
    //         text = '';
    //     newElem.text().split('').forEach(function(val, i){
    //         if (val != oldText.charAt(i)){
    //             text += `<span class='highlight-${type}'>`+val+"</span>";
    //         } else {
    //             text += val;            
    //         }
    //     });
    //     newElem.html(text); 
    // }

    var winnerGetDate = ''
    $(document).on("change", ".winner_get_date", function () {
        winnerGetDate = $(this).val();
    })

    var statisticsData = '';

    $(document).on("click", ".open-statistics-modal", function () {
        const tenderCpvId = $(this).parents('tr').attr('data-id');
        const estimatedPrice = $(this).parents('tr').find('.estimated-price').val();
        let unit = $(this).parents('tr').find('.unit').val();
        let count = $(this).parents('tr').find('.count').val();
        let specification = $(this).parents('tr').find('.specification').val();
        const currentCpv = cpvs.find(item => +item.id === +tenderCpvId);
        const unitName = currentCpv.cpv_data.unit ? currentCpv.cpv_data.unit : 'դրամ';
        $.ajax({
            type: "GET",
            url: `/admin/cpv/get_cpv_type/${currentCpv.cpv_id}`,
            success: (data) => {
                if(data === 'work' || data === 'service'){
                    unit = 'դրամ';
                    count = 1;
                    // specification = currentCpv.cpv_name
                    if(data === 'work'){
                        specification = currentCpv.cpv_name
                        // specification = 'Համաձայն նախագծանահաշվային փաստաթղթերի';
                    }
                }
                if(unit){
                    unit = unit.toLowerCase();
                    if(unit === 'կգ'){
                        unit = 'կիլոգրամ';
                    }
                    $('#unit option').each(function(index) {
                        if($(this).html() === unit){
                            $(this).prop("selected", true)
                        }
                    });
                } else {
                    $('#unit option').each(function(index) {
                        if($(this).html() === unitName){
                            $(this).prop("selected", true)
                        }
                    });
                }
                if(count){
                    $('#count').val(count);
                }
                if(specification){
                    $('#specification').val(specification);
                    $('#specification').trigger('keyup');
                }
            }
        });
        $.ajax({
            type: "GET",
            url: `/admin/specifications/get_by_cpv_id/${currentCpv.cpv_id}`,
            dataType: "json",
            success: function(data){
                specifications = data;
                $('#specifications').val(JSON.stringify(specifications));
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log("XMLHttpRequest", XMLHttpRequest);
                console.log("textStatus", textStatus);
                console.log("errorThrown", errorThrown);
            }
        });
        if(winnerGetDate){
            $('#winner_get_date').val(winnerGetDate)
        }
        if(unit){
            $('#unit option').each(function(index) {
                if($(this).html() === unit){
                    $(this).prop("selected", true)
                }
            });
        } else {
            $('#unit option').each(function(index) {
                if($(this).html() === unitName){
                    $(this).prop("selected", true)
                }
            });
        }
        $('#selected_cpv').val(currentCpv.cpv_id);
        $('#selected_tender_cpv').val(tenderCpvId);

        if(count){
            $('#count').val(count);
        }
        if(specification){
            $('#specification').val(specification);
            $('#specification').trigger('keyup');
        }

        const statistics = currentCpv.statistics;

        if(estimatedPrice){
            $('#estimated_price').val(estimatedPrice);
        }
        if(statistics){
            statisticsData = statistics;

            specification = statistics.specification

            $('#specification_id').val(statistics.specification_id)

            $('#selected_cpv_statistics').val(statistics.id);
            // Set Region 
            $('#region option').filter(function(index, item) {
                return +$(this).val() === +statistics.region_id;
            }).prop("selected", true);

            // Set Unit 
            $('#unit option').filter(function(index, item) {
                return +$(this).val() === +statistics.unit_id;
            }).prop("selected", true);

            // Set Established
            if(statistics.established){
                $('#established').prop("checked", true);
            } else {
                $('#failed').prop("checked", true);
                $('#failedVariants').show();
            }
            $('#failedVariants option').filter(function(index, item) {
                return $(this).val() === statistics.failed_substantiation;
            }).prop("selected", true);

            // Set Other Values
            const inputs = ['estimated_price', 'specification', 'failed_substantiation', 'count', 'estimated_price', 'winner_get_date']
            inputs.forEach((input, index) => {
                if(statistics[input]){
                    $(`#${input}`).val(statistics[input]);
                };
            })

            if(statistics.participants){
                statistics.participants.forEach((participant, index) => {
                    const currIndex = index + 1;
                    $('#tbody').append(`
                    <tr data-index="${currIndex}" id="R${currIndex}">
                        <td class="text-center">
                            <button class="btn btn-danger remove d-flex align-items-center justify-content-center" type="button">
                                <i class="fas fa-minus-circle" style="font-size: 16px"></i>
                            </button>
                        </td>
                        <td class="row-currIndex text-center">
                            <input ${participant.is_winner && 'checked'} type="radio" id="winner" name="winner" value="${currIndex}">
                        </td>
                        <td class="row-currIndex text-center">
                            <input value="`+participant.name+`" id="name_${currIndex}" type="text" class="form-control name-input">
                            <ul id="list_${currIndex}" class="list-group"> 
                                <li id="1" class="list-group-item d-flex justify-content-between flex-column align-items-center">
                                </li>
                            </ul>
                        </td>
                        <td class="row-currIndex text-center">
                            <input value=${participant.value} id="value_${currIndex}" type="number" class="price-input form-control">  
                        </td>
                        <td class="row-currIndex text-center">
                            <input value=${participant.vat} id="vat_${currIndex}" type="number" class="vat-input form-control"> 
                        </td>
                        <td class="row-currIndex text-center">
                            <input value=${participant.total} id="total_${currIndex}" type="number" class="form-control"> 
                        </td>
                    </tr>`);
                })
            }
        }
    })

    $(document).on("keyup", ".name-input", function () {
        const query = $(this).val();
        const listId = $(this).parent().find('.list-group').attr('id');
        searchAPI(query, listId);
    });

    $(document).on("click", ".participant-list-result", function () {
        const text = $(this).html();
        $(this).parents('td').find('.name-input').val(text)
        $(this).parents('td').find('.list-group').hide();
        
    });

    function searchAPI(query, listId) {
        if(query){
            $(`#${listId}`).show();
            const data = {
                list_id: listId,
                query
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "/admin/statistics/searchParticipantName",
                dataType: "json",
                data: data,
                success: function(data){
                    const filteredData = data.splice(0, 5)
                    $(`#${listId}`).html('');
                    filteredData.forEach((item, index) => {
                        $(`#${listId}`).append(`
                            <li class="list-group-item d-flex justify-content-start flex-column align-items-center p-2">
                                <p class="participant-list-result cursor-pointer text-left w-100 m-0" id="elem${index}">${item.name}</p>
                            </li>
                        `);
                    })
                },
                error: function(data){
                    alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
                },
            });
        }
    }

    $("#exampleModalCenter").on("hidden.bs.modal", function () {
        const tenderCpvId = $('#selected_tender_cpv').val();
        $('#exampleModalCenter option').attr('selected', false);
        // $('#exampleModalCenter input').prop('checked', false); 
        $('#exampleModalCenter input').val('');
        $('#exampleModalCenter textarea').val('');
        $('#established').val('1');
        $('#failed').val('0');
        $('#specification-list').html('');
        $('#failedVariants').hide();
        $('#tbody').html('');
    });

    $(document).on("change", "input[name='variant']", function () {
        if(+$('input[name=variant]:checked').val()){
            $('#failedVariants').hide();
        } else {
            $('#failedVariants').show();
        }
    })

    $(document).on("click", ".saveCpvStatistics", function () {
        const participants = [];
        $('#tbody tr').each(function(index) {
            const participantData = {
                name: $(this).find(`#name_${index + 1}`).val(),
                value: +$(this).find(`#value_${index + 1}`).val(),
                vat: +$(this).find(`#vat_${index + 1}`).val(),
                total: +$(this).find(`#total_${index + 1}`).val(),
            }
            participants.push(participantData);
        });
        const winnerIndex = $('#winner:checked').val();
        if(winnerIndex){
            participants[winnerIndex - 1].is_winner = true;
            participants.map((item, index) => index !== winnerIndex - 1 ? item.is_winner = false : '');
        }
        const statisticsData = {
            participants: participants,
            cpv_id: +$('#selected_cpv').val(),
            specification_id: +$('#specification_id').val(),
            tender_state_cpv_id: $('#selected_tender_cpv').val(),
            region_id: +$('#region option').filter(':selected').val(),
            unit_id: $('#unit option').filter(':selected').val(),
            estimated_price: $('#estimated_price').val(),
            specification: $('#specification').val(),
            count: +$('#count').val().replace(',', ''),
            winner_get_date: $('#winner_get_date').val(),
            established: +$('input[name=variant]:checked').val(),
            failed_substantiation: $('#failedVariants option').filter(':selected').val(),
        }
        const statistcsId = +$('#selected_cpv_statistics').val();
        $.ajax({
            type: "POST",
            url: `${statistcsId ? `/admin/statistics/updateCpvStatistics/${statistcsId}` : "/admin/statistics/setCpvStatistics"}`,
            dataType: "json",
            data: statisticsData,
            success: function(data){
                $("#exampleModalCenter .close").click()
                const tenderCpvId = $('#selected_tender_cpv').val();
                $('.cpv-table tr').each(function(index) {
                    const currentTenderCpvId = $(this).attr('data-id')
                    if(currentTenderCpvId === tenderCpvId){
                        // const button = $(this).find('.open-statistics-modal')
                        // button.addClass('btn-success').removeClass('btn-primary')
                        getSelectedTenderCpvs()
                    }
                });
            },
            error: function(data){
                alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
            },
        });
    })
    

    $(document).on("input", ".searchTenderState", function () {
        var password = $(this).val().trim();
        if(password.length == 0){
            $(".searchTenderState-sel").hide();
            $(".is_competition_section").show();
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
              type: "POST",
              url: "/admin/get/tender_state/by/password",
              dataType: "json",
              data: {password:password},
              success: function(data){
                $(".searchTenderState-sel").html(" ");
                if(data.length == 0){
                    $(".searchTenderState-sel").append("<option value='0' disabled selected> Տվյալ ծածկագորվ ոչինչ չի գտնվել </option>");
                }else{
                    $(".searchTenderState-sel").append("<option value='0' disabled selected> - Ընտրել- </option>");
                }
                for( i=0; i < data.length; i++ ){
                     $(".searchTenderState-sel").append("<option value='"+data[i].id+"' data-organizator='"+data[i].organizer_id+"' data-name='"+data[i].tdName+"' data-start_date = '"+data[i].start_date+"' data-end_date='"+data[i].end_date+"' data-cpv='"+data[i].cpv+"' data-kind='"+data[i].kind+"' data-is_competition = '"+data[i].is_competition+"' data-is_new = '"+data[i].is_new+"' data-is_closed = '"+data[i].is_closed+"' data-estimated='"+data[i].estimated+"' data-estimated_file = '"+data[i].estimated_file+"' data-estimated_price = '"+data[i].estimated_price+"' data-invitation_file='"+data[i].invitation_link+"' data-procedure='"+data[i].procedure_type+"' data-password='"+data[i].password+"' data-regions = '"+data[i].regions+"' data-type = '"+data[i].type+"' data-guaranteed = '"+data[i].guaranteed+"'>Ծածկագիր։ "+data[i].password+" Պատվիրատու։ "+data[i].name+"</option>");
                }
                $(".searchTenderState-sel").show();
              },
            });
        }
    })

    $(document).on("input", ".cpv-table .select2-search__field", function () {
        var cpv = $(this).val().trim();
        var checkCpvExist = false;
        $(".cpv-table").find('tr[data-key]').remove();
        $("#cpvSelect").empty();
        $(".view-id-inp").val(" ");
        $(".cpv-name-inp").val(" ");
        $(".cpv-unit-inp").val(" ");
        $(".cpv-count-inp").val(" ");
        $(".cpv-specification-inp").val(" ");
        $(".estimated-price-inp").val(" ");
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
                                <td></td>
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

                        cpvsCount++
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
        $(".cpv-unit-inp").val(" ");
        $(".cpv-count-inp").val(" ");
        $(".cpv-specification-inp").val(" ");
        $(".estimated-price-inp").val(" ");
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

    $(document).on("input", ".cpv-unit-inp", function () {
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
            row.find("td:nth-child(4) input").val(text);
        }
    })

    $(document).on("input", ".cpv-count-inp", function () {
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
            row.find("td:nth-child(5) input").val(text);
        }
    })

    $(document).on("input", ".cpv-specification-inp", function () {
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
            row.find("td:nth-child(6) input").val(text);
        }
    })

    $(document).on("input", ".estimated-price-inp", function () {
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
            row.find("td:nth-child(7) input").val(text);
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
    
    $("#parser-edit-kind").change(function(){
        if( $('#parser-edit-kind').is(':checked')) {
            $("#tender-edit-kind").attr("disabled","disabled");
        }else{
            $("#tender-edit-kind").removeAttr("disabled");
        }
    })

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
                            children = getJson(val.children);
                            x[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
                        })
                        cb(x);
                    });
                };
        $('#tree').jstree(true).refresh();

    }); 

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

    function getJsonCategory(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" :val.name, "children":children };
        })
        return t;
    }
    function getJson(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
        })
        return t;
    }


    $(document).ready(function () {
        $('#addBtn').on('click', function () {
            const lastIndex = $('#tbody tr:last-child').attr('data-index') || 0;
            const currIndex = +lastIndex + 1;
            $('#tbody').append(`
            <tr data-index="${currIndex}" id="R${currIndex}">
                <td class="text-center">
                    <button class="btn btn-danger remove d-flex align-items-center justify-content-center"
                    type="button"><i class="fas fa-minus-circle" style="font-size: 16px"></i></button>
                </td>
                <td class="row-index text-center">
                    <input type="radio" id="winner" name="winner" value="${currIndex}">
                </td>
                <td class="row-index text-center">
                    <input id="name_${currIndex}" type="text" class="form-control name-input">
                    <ul id="list_${currIndex}" class="list-group"> 
                        <li id="1" class="list-group-item d-flex justify-content-between flex-column align-items-center">
                        </li>
                    </ul>
                </td>
                <td class="row-index text-center">
                    <input id="value_${currIndex}" type="number" class="price-input form-control">  
                </td>
                <td class="row-index text-center">
                    <input id="vat_${currIndex}" type="number" class="vat-input form-control"> 
                </td>
                <td class="row-index text-center">
                    <input id="total_${currIndex}" type="number" class="form-control"> 
                </td>
            </tr>`);
        });
        $('#tbody').on('click', '.remove', function () {
            var child = $(this).closest('tr').nextAll();
            child.each(function () {
                var id = $(this).attr('id');
                var idx = $(this).children('.row-index').children('p');
                var dig = parseInt(id.substring(1));
                idx.html(`Row ${dig - 1}`);
                $(this).attr('id', `R${dig - 1}`);
            });
            $(this).closest('tr').remove();
        });
    });

    $('#failedVariants').hide()
    $('input[name=variant]').on("click", function(e){
        if(+e.target.value === 0){
            $('#failedVariants').show()
        }else{
            $('#failedVariants').hide()
        }
    });
</script>
<style>
    .modal {
        overflow-y:auto;
    }
    .cpv-table .select2-selection__rendered{
        overflow-y: scroll !important;
        display: flex !important;
        flex-wrap: wrap;
        height: 95px !important;
        width: auto !important;
    }
    .highlight-new {background-color: #B4D5FF}
    .highlight-old {background-color: #ffc9b4}
</style>
@stop