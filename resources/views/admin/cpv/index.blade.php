@extends('admin.layout.main')
@section('breadcrumb_second') Cpv @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Cpv Ցանկ @stop
@section('content')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
   <div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <input type="hidden" id="units" value="{{ $units }}">
                <input type="hidden" id="regions" value="{{ $regions }}">
                {{ Form::model($cpv,array('route' => array('cpv.fileUploade'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                @method('POST')
                <div class="form-group row">
                    <div class="col-xl-12">
                        <h4 class="mt-0 header-title"> Կցել ֆայլ </h4>
                        <input type="file" id="input-file-now-custom-2" name="file" class="dropify" >
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('file') }} </p>
                    </div>
                </div>
                <div class="form-group" style="float: left;">
                    <a href="/admin/cpv"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                    <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                {{Form::model($cpv,array('route' => array('cpv.fileUploadeTranslates'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
                @method('POST')
                <div class="form-group row">
                    <div class="col-xl-12">
                        <h4 class="mt-0 header-title"> կցել թարգմանությունների ֆայլ </h4>
                        <input type="file" id="input-file-now-custom-2" name="fileTr" class="dropify" >
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('fileTr') }} </p>
                    </div>
                </div>
                <div class="form-group" style="float: left;">
                    <a href="/admin/cpv"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                    <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <button class="upload-file-input-first btn btn-primary buttons-excel buttons-html5 mb-3 mr-3">Մաքրել շուկայի պոտենցիալները</button>
                <input class="upload-file-input" type="file" id="upload" accept=".xlsx" hidden/>
                <label class="btn btn-primary buttons-excel buttons-html5 mb-3" for="upload">Ներբեռնել շուկայի պոտենցիալը (Քայլ 2)</label>
                <i class="fa fa-spinner fa-spin" style='font-size: 26px; display: none;'></i>
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Cpv</th>
                                <th>Անվանում</th>
                                <th>Չափման միավոր</th>
                                <th>Անվանումը Ռուսերեն</th>
                                <th>Չափման միավորը Ռուսերեն</th>
                                <th>Թարմացվել է</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="potentialModal" tabindex="-1" role="dialog" aria-labelledby="potentialModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Շուկայի պոտենցիալ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 my-2">
                            <span class="h5">Շուկայի պոտենցիալ ընդհանուր՝ </span>
                            <span class="potential-total text-primary">0</span>
                        </div>
                        <div class="col-12 my-2">
                            <span class="h5">Շուկայի պոտենցիալ ըստ Էլեկտրոնային մրցույթների՝ </span>
                            <span class="potential-electronic text-primary">0</span>
                            <small><span class="potential-electronic-updated"></span></small>
                        </div>
                        <div class="col-12 my-2">
                            <span class="h5">Շուկայի պոտենցիալ ըստ թղթային մրցույթների՝ </span>
                            <span class="potential-paper text-primary">0</span>
                        </div>
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="col-9">
                                    <input placeholder="ավելացնել թղթային մրցույթների պոտենցիալ" class="potential-value form-control" type="number">
                                </div>
                                <div class="col-3">
                                    <button class="add-potential-value btn btn-primary w-100">
                                        Ավելացնել
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statisticsModal" tabindex="-10" role="dialog" aria-labelledby="statisticsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70% !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Վիճակագրություն 
                        <span class="h6">(օգտագործվել է <span class="used-count"></span> անգամ)</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="accordion"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade merge-modal" id="mergeSpecificationModal" tabindex="-1" role="dialog" aria-labelledby="mergeSpecificationModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 50% !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Միավորել այլ Տեխնիկական բնութագրի հետ
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="col-12">
                            {{-- <h2 class="h4 mb-1">Choose from our pizzas</h2> --}}
                            <p class="small text-muted font-italic mb-4">Միավորելու համար ընտրեք ներկայացված տեխ․ բնութագիրներից մեկը և պահպանեք</p>
                            <ul class="list-group"></ul>
                            <button class="btn btn-primary merge-specification-save float-right mt-3">
                                Պահպանել
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop
@section('scripts')
<script src="{{asset('/assets/back/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/buttons.colVis.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script src="/assets/back/assets/js/waves.js"></script>
<script src="/assets/back/assets/js/feather.min.js"></script>
<script src="/assets/back/assets/js/jquery.slimscroll.min.js"></script>
<script src="/assets/back/plugins/apexcharts/apexcharts.min.js"></script>

<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js') }}"></script>

<script>
    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        moment.locale('hy-am');

        

        $('.upload-file-input-first').click(function (e) { 
            $('.fa-spinner').show();                          
            $.ajax({
                url: '/admin/cpv/uploadPotentialClear',
                dataType: 'text', 
                cache: false,
                contentType: false,
                processData: false,
                type: 'post',
                success: function(php_script_response){
                    if(php_script_response === 'success'){
                        alert('Տվյալները շուտով կթարմացվեն')
                    }
                    $('.fa-spinner').hide();                          
                }
            });
        });

        $('.upload-file-input').change(function (e) { 
            e.preventDefault();
            var file_data = $(this).prop('files')[0]; 
            if(file_data){
                var form_data = new FormData();                  
                form_data.append('file', file_data);
                console.log(form_data)  
                $('.fa-spinner').show();                          
                $.ajax({
                    url: '/admin/cpv/uploadPotential',
                    dataType: 'text',  // <-- what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                    success: function(php_script_response){
                        if(php_script_response === 'success'){
                            alert('Տվյալները շուտով կթարմացվեն')
                        }
                        $('.fa-spinner').hide();                          
                    }
                });
            }
        });

        $("#datatable-buttons").DataTable({
            processing: true,
            serverSide: true,
            ajax: '/admin/cpv/tableData',
            columns: [
                { data: 'code', name: 'code'},
                { data: 'name', name: 'name'},
                { data: 'unit', name: 'unit'},
                { data: 'name_ru', name: 'name_ru'},
                { data: 'unit_ru', name: 'unit_ru'},
                { data: 'updated_at', name: 'updated_at'},
                { data: 'action', name: 'action'},
            ],
            dom: 'Bflrtip',
            buttons: ["excel"],
            createdRow: function (row, data, index) {
                if (data.specifications_with_statistics.length) {
                    console.log('HERE')
                    console.log(row)
                    $(row).addClass('bg-light');
                }
            },
            "lengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Բոլորը"]],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Armenian.json"
            }
        }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#row_callback").DataTable({
            createdRow: function (t, a, e) {
                15e4 < 1 * a[2].replace(/[\$,]/g, "") && $("td", t).eq(2).addClass("highlight")
            }
        })
        
        $(document).on("click", ".get-statistics", function () {
            const specificationId = $(this).attr('data-id');
            const potential = $(this).attr('potential');
            const startDate = $(`#date-range-${specificationId}`).data('daterangepicker').startDate.format('YYYY-MM-DD');
            const endDate = $(`#date-range-${specificationId}`).data('daterangepicker').endDate.format('YYYY-MM-DD');
            const unit = +$(`#unit-${specificationId} :selected`).val() || null;
            const region = +$(`#region-${specificationId} :selected`).val() || null;
            if(startDate && endDate && unit){
                const requestObj = {
                    startDate, endDate, unit, region
                }
                $.ajax({
                    type: "POST",
                    // url: "{{ env("APP_URL").'/admin/statistics/getCpvStatistics/' }}" + specificationId,
                    url: '/admin/statistics/getCpvStatistics/' + specificationId,
                    dataType: "json",
                    data: requestObj,
                    success: function(data){
                        if(data.length){
                            let minEstimatedPrice = 0,
                                maxEstimatedPrice = 0,
                                avgEstimatedPrice = 0,
                                minPresentedPrice = 0,
                                minPresentedPriceParticipant = 0,
                                maxPresentedPrice = 0,
                                avgPresentedPrice = 0, 
                                popularPrice = 0,
                                avgParticipantsCount = 0,
                                planPriceSummary = 0,
                                estimatedPriceSummary = 0;

                            const establishedCount = data.filter(item => item.established).length;
                            const unestablishedCount = data.filter(item => !item.established).length;
                            const notMatchConditionsCount = data.filter(item => !item.established && item.failed_substantiation === 'not_match_conditions').length;
                            const notRequirementPurchaseCount = data.filter(item => !item.established && item.failed_substantiation === 'not_requirement_purchase').length;
                            const noSubmittedApplicationCount = data.filter(item => !item.established && item.failed_substantiation === 'no_submitted_application').length;
                            const noContractSignedCount = data.filter(item => !item.established && item.failed_substantiation === 'no_contract_signed').length;
                            if(establishedCount){
                                minEstimatedPrice = Math.min(...data.filter(item => item.established).map(item => item.estimated_price_unit));
                                maxEstimatedPrice = Math.max(...data.filter(item => item.established).map(item => item.estimated_price_unit));
                                avgEstimatedPrice = Math.round(data.filter(item => item.established).map(item => item.estimated_price_unit).reduce((a, b) => a + b, 0) / establishedCount, 2);
                                const participants = data.filter(item => item.established).map(item => item.participants).reduce((a, b) => a ? [...a, ...b] : [...b], 0)
                                if(participants){
                                    minPresentedPrice = Math.min(...participants.map(item => item.total_unit));
                                    minPresentedPriceParticipant = participants.find((item) => item.total_unit === minPresentedPrice).name;
                                    maxPresentedPrice = Math.max(...participants.map(item => item.total_unit));
                                    avgPresentedPrice = Math.round(participants.map(item => item.total_unit).reduce((a, b) => a + b, 0) / participants.length, 2);
                                    popularPrice = participants.map(item => item.total_unit).reduce((a,b,i,arr) => (arr.filter(v => v === a).length >= arr.filter(v => v === b).length ? a : b), 0);
                                }
                                avgParticipantsCount = data.filter(item => item.established).map(item => item.participants.length).reduce((a, b) => a + b, 0) / data.filter(item => item.established).length;
                                planPriceSummary = potential;
                                estimatedPriceSummary = data.filter(item => item.established).map(item => item.estimated_price).reduce((a, b) => a + b, 0);
                            }
                            

                            $(`#statistics-result-${specificationId}`).html(`
                                <div class="table-responsive">
                                    <table class="table table-bordered responsible-table text-center">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Կազմակերպված /վերլուծված/ տենդերների քանակը</th>
                                                <th rowspan="2">Կայացած գնման ընթացակարգերի քանակը</th>
                                                <th rowspan="2">Չկայացած գնման ընթացակարգերի քանակը՝ ըստ հիմքերի</th>
                                                <th colspan="3">Նախահաշվային գինը</th>
                                                <th colspan="3">Մասնակիցների գները</th>
                                                <th rowspan="2">Ամենաշատ ներկայացված գները</th>
                                                <th rowspan="2">Մասնակցության միջին քանակը /մրցակցությունը/</th>
                                                <th rowspan="2">Շուկայի պոտենցիալը՝ համաձայն հրապարակված գնումների պլանների</th>
                                                <th rowspan="2">Կայացած գնման գործընթացների ծավալը</th>
                                            </tr>
                                            <tr>
                                                <th>Նվազագույն</th>
                                                <th>Միջին</th>
                                                <th>Առավելագույն</th>
                                                <th>Նվազագույն</th>
                                                <th>Միջին</th>
                                                <th>Առավելագույն</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>${data.length}</td>
                                                <td>${establishedCount}</td>
                                                <td class="${unestablishedCount && 'text-left px-0'}">
                                                    ${
                                                        unestablishedCount ? 
                                                        `<ul class="pl-3 ml-1 pr-1">
                                                            <li>հայտերից ոչ մեկը չի համապատասխանում հրավերի պայմաններին՝ ${notMatchConditionsCount}</li>
                                                            <li>դադարում է գոյություն ունենալ գնման պահանջը՝ ${notRequirementPurchaseCount}</li>
                                                            <li>ոչ մի հայտ չի ներկայացվել՝ ${noSubmittedApplicationCount}</li>
                                                            <li>պայմանագիր չի կնքվում՝ ${noContractSignedCount}</li>
                                                        </ul>` : 0
                                                    }
                                                </td>
                                                <td>${minEstimatedPrice ? `${minEstimatedPrice}դր․` : '-'}</td>
                                                <td>${avgEstimatedPrice ? `${avgEstimatedPrice}դր․` : '-'}</td>
                                                <td>${maxEstimatedPrice ? `${maxEstimatedPrice}դր․` : '-'}</td>
                                                <td>${minPresentedPrice ? `${minPresentedPrice}դր․ (${minPresentedPriceParticipant})` : '-'}</td>
                                                <td>${avgPresentedPrice ? `${avgPresentedPrice}դր․` : '-'}</td>
                                                <td>${maxPresentedPrice ? `${maxPresentedPrice}դր․` : '-'}</td>
                                                <td>${popularPrice ? `${popularPrice}դր․` : '-'}</td>
                                                <td>${avgParticipantsCount ? avgParticipantsCount : '-'}</td>
                                                <td>${planPriceSummary ? `${planPriceSummary}դր․` : '-'}</td>
                                                <td>${estimatedPriceSummary ? `${estimatedPriceSummary}դր․` : '-'}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            `)
                        } else {
                            $(`#statistics-result-${specificationId}`).html(
                                `<div class="col-12 text-center">Վիճակագրություն առկա չէ</div>`
                            )
                        }
                    },
                    error: function(data){
                        alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
                    },
                });
            } else {
                alert('Խնդրում ենք լրացնել պարտադիր ֆիլտրերը')
            }
        });

        var specificationsDataGlobal = [];
        var selectedSpecification = {};

        $(document).on("click", ".merge-specification-save", function () {
            const selectedMainSpecificationJSON = $('.specification-radio:checked').attr('specification-data');
            if(selectedMainSpecificationJSON){
                const selectedMainSpecification = JSON.parse(selectedMainSpecificationJSON);
                const data = {
                    selectedSpecification,
                    selectedMainSpecification
                }
                $.ajax({
                    type: "POST",
                    url: '/admin/statistics/mergeCpvStatistics',
                    dataType: "json",
                    data: data,
                    success: function(data){
                        if(data){
                            $('#mergeSpecificationModal').modal('hide')
                            $('#statisticsModal').modal('hide')
                            $('#datatable-buttons').DataTable().ajax.reload();
                        }
                    },
                    error: function(data){
                        alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
                    },
                });

            } else {
                alert('Ընտրեք անհրաժեշտ տեխ․ բնութագիրները')
            }
        })
        

        $(document).on("click", ".edit-specification-button", function () {
            const block = $(this).parents('.mb-0');
            block.find('.edit-specification-block').show();
            block.find('.specification-button').hide();
            $(this).hide();
        })

        $(document).on("click", ".edit-specification-button-save", function () {
            const block = $(this).parents('.mb-0');
            block.find('.edit-specification-button').show();
            block.find('.specification-button').show();
            block.find('.edit-specification-block').hide();

            const newValue = block.find('textarea').val()
            const specificationDataJson = block.find('.merge-specification-button').attr('specification-data');
            const specificationData = JSON.parse(specificationDataJson)

            $.ajax({
                type: "PUT",
                url: '/admin/specifications/' + specificationData.id,
                dataType: "json",
                data: {description: {hy: newValue, ru: ''}},
                success: function(data){
                    const newDescription = data.description.hy;
                    console.log(newDescription)
                    block.find('textarea').val(newDescription)
                    block.find('.specification-button').html(newDescription)
                },
                error: function(data){
                    alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
                },
            });

        })

        $(document).on("click", ".merge-specification-button", function () {
            const specificationData = JSON.parse($(this).attr('specification-data'));
            selectedSpecification = specificationData;
            console.log(specificationData)
            $('.list-group').html('')
            specificationsDataGlobal.forEach((specification) => {
                if(specification.id !== specificationData.id){
                    let specificationJson = JSON.stringify(specification)
                    $('.list-group').append(`
                        <li class="list-group-item rounded-0">
                            <div class="custom-control custom-radio">
                                <input specification-data='${specificationJson}' class="custom-control-input specification-radio" id="customRadio${specification.id}" type="radio" name="customRadio">
                                <label class="custom-control-label" for="customRadio${specification.id}">
                                    <p class="mb-0">${specification.description.hy}</p>
                                </label>
                            </div>
                        </li>
                    `);
                }
            })
        })


        $(document).on("click", ".open-statistics-modal", function () {
            const specificationsData = JSON.parse($(this).attr('data'));
            const potentialData = JSON.parse($(this).attr('potential'));
            const units = JSON.parse($('#units').val());
            const regions = JSON.parse($('#regions').val());
            specificationsDataGlobal = specificationsData;
            $('.used-count').html(potentialData.used_count);
            $('#accordion').html('');
            if(specificationsData.length){
                specificationsData.forEach((specification) => {
                    let specificationJson = JSON.stringify(specification)
                    $('#accordion').append(`
                        <div class="card">
                            <div class="card-header" id="heading-${specification.id}">
                                <div class="mb-0 d-flex justify-content-between align-items-center">
                                    <div class="col-8 py-0 pl-0">
                                        <button class="specification-button btn btn-link collapsed" data-toggle="collapse" data-target="#collapse-${specification.id}" aria-expanded="false" aria-controls="collapse-${specification.id}">
                                            ${specification.description.hy}
                                        </button>
                                        <div class="edit-specification-block">
                                            <textarea rows="6" class="w-100 form-control" type="text">${specification.description.hy}</textarea>
                                            <button class="col-auto mt-2 btn btn-success edit-specification-button-save">
                                                Պահպանել
                                            </button>  
                                        </div>
                                    </div>
                                    <div class="col-4 py-0 pr-0 d-flex flex-row">
                                        <button class="col-auto mr-2 btn btn-primary edit-specification-button float-right">
                                            Խմբագրել
                                        </button>
                                        <button specification-data='${specificationJson}' data-toggle='modal' data-target='#mergeSpecificationModal' class="col-auto btn btn-primary merge-specification-button float-right">
                                            Միավորել այլ Տեխ․ բնութագրի հետ
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="collapse-${specification.id}" class="collapse" aria-labelledby="heading-${specification.id}" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-4">
                                            <label for="date-range-${specification.id}">Ժամանակահատվածը*</label>
                                            <input id="date-range-${specification.id}" class="form-control" name="date-range">
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="unit-${specification.id}">Չափման միավոր*</label>
                                            <select class="form-control" id="unit-${specification.id}">
                                                <option disabled selected>Ընտրել</option>
                                                ${units.map(unit => `<option value=${unit.id}>${unit.title.hy}</option>`)}
                                            </select>
                                        </div>
                                        <div class="form-group col-3">
                                            <label for="region-${specification.id}">Տարածաշրջանը</label>
                                            <select class="form-control" id="region-${specification.id}">
                                                <option value="0" selected>Ընտրել</option>
                                                ${regions.map(unit => `<option value=${unit.id}>${unit.name}</option>`)}
                                            </select>
                                        </div>
                                        <div class="form-group col-2 row align-items-end">
                                            <button data-id="${specification.id}" potential="${potentialData.potential_electronic + potentialData.potential_paper}" class="col w-100 btn btn-primary get-statistics">Ստանալ</button>
                                        </div>
                                        <div id="statistics-result-${specification.id}" class="col-12 row"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $(`#date-range-${specification.id}`).daterangepicker({
                        numberOfMonths : 2,
                        initialText : 'Select period...',
                        locale: {
                            "applyLabel": "Պահպանել",
                            "cancelLabel": "Չեղարկել",
                        }
                    });
                })
            } else {
                $('#accordion').html(
                    `<div class="col-12 text-center">Վիճակագրություն առկա չէ</div>`
                )
            }
        })

        $(document).on("click", ".open-potential-modal", function () {
            const potentialData = JSON.parse($(this).attr('data'));
            const year = new Date().getFullYear();
            const potentialElectronic = JSON.parse(potentialData.potential_electronic);
            $('.potential-total').html(`${potentialElectronic[year] + potentialData.potential_paper}դր.`)
            $('.potential-electronic').html(`${potentialElectronic[year]}դր.`)
            $('.potential-paper').html(`${potentialData.potential_paper}դր.`)
            $('.potential-electronic-updated').html(`(Վերջին թարմացումը ${potentialData.updated})`)
            $('.add-potential-value').attr('cpv-id', potentialData.cpv_id);
        })

        $(document).on("click", ".add-potential-value", function () {
            const potentialValue = $('.potential-value').val();
            const cpvId = $(this).attr('cpv-id');
            $.ajax({
                type: "POST",
                url: '/admin/statistics/setCpvPotential/' + cpvId,
                dataType: "json",
                data: {potentialValue},
                success: function(data){
                    $('.potential-total').html(`${data.potential_electronic + data.potential_paper}դր.`)
                    $('.potential-paper').html(`${data.potential_paper}դր.`)
                    $('.potential-value').val('');
                },
                error: function(data){
                    alert('Խնդրում ենք լրացնել բոլոր դաշտերը')
                },
            });
        })

	})
</script>
<style>
    .edit-specification-block{
        display: none;
    }
    .merge-modal.fade{
        background: rgba(0, 0, 0, 0.35);
        z-index: 10000000 !important;
    }
</style>
@stop
