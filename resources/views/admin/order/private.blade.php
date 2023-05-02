@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Տեղեկատվություն @stop
@section('breadcrumb_active') Մասնավոր @stop
@section('page_title') Վճարումների Ցանկ @stop
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
        <div class="card-body">
        <a href="/admin/info/create" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a>
            <div class="table-responsive">
                <table id="datatable-buttons" class="table  table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>Հերթական համարը</th>
                        <th>Օգտատեր</th>
                        <th>Փաթեթի տեսակ</th>
                        <th>Գնման օր</th>
                        <th>Ավարտի օր</th>
                        <th>Փոփոխության ամսաթիվ</th>
                        <th>Վճարման եղանակ</th>
                        <th>Վճարված գումար</th>
                        <th>Գործողություններ</th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
            </div>

        </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!--end row-->
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

<script src="/assets/back/assets/js/waves.js"></script>
<script src="/assets/back/assets/js/feather.min.js"></script>
<script src="/assets/back/assets/js/jquery.slimscroll.min.js"></script>
<script src="/assets/back/plugins/apexcharts/apexcharts.min.js"></script>
<script>
    $(document).ready(function () {
        $("#datatable-buttons").DataTable({
            processing: true,
            // serverSide: true,
            ajax: '/admin/order/tableData',
            columns: [
                { data: 'id', name: 'id'},
                { data: 'userName', name:'userName'},
                { data: 'packageName', name: 'packageName'},
                { data: 'strat_date', name: 'strat_date'},
                { data: 'end_date', name: 'end_date'},
                { data: 'updated_at', name: 'updated_at'},
                { data: 'payment_method', name:'payment_method'},
                { data: 'amount', name:'amount'},
                { data: 'action', name:'action'},
            ],
            dom: 'Bflrtip',
            buttons: ["excel", "pdf"],
            lengthMenu: [[10, 50,100, -1], [10, 50,100, "Բոլորը"]],
            order: [[ 3, "desc" ]],
            createdRow: function( row, data, dataIndex ) {
                $(row).addClass(data.className)
            },
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Armenian.json"
            }
        }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#row_callback").DataTable({
        })
	})

</script>
<style type="text/css">
    .orange{
        background-color: #FFFD40!important;
        color: black!important;
    }
    .trial{
        background-color:#44909B!important;
        color: white!important;
    }
    .red{
        background-color: #C47172!important;
        color: white!important;
    }
    .white{
        background-color: #FFFFFF!important;
        color: black!important;
    }
</style>
@stop
