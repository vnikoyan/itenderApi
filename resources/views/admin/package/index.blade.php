@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Փաթեթ @stop
@section('breadcrumb_active') Մասնավոր փաթեթներ @stop
@section('page_title') Փաթեթների Ցանկ@stop
@section('content')
<div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-body">
                           <div class="table-responsive">
                              <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                 <thead>
                                    <tr>
                                       <th>Փաթեթ</th>
                                       <th>1 Ամիս</th>
                                       <th>3 Ամիս</th>
                                       <th>6 Ամիս</th>
                                       <th>1 Տարի</th>
                                       <th>Գործողություն</th>
                                    </tr>
                                 </thead>
                                 <tbody>

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
            serverSide: true,
            ajax: '/admin/package/tableData',
            columns: [
                { data: 'package', name: 'package'},
                { data: 'price_1', name: 'price_1'},
                { data: 'price_3', name: 'price_3'},
                { data: 'price_6', name: 'price_6'},
                { data: 'price_12', name: 'price_12'},
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'flrtip',
            "lengthMenu": [[10, 50,100, -1], [10, 50,100, "Բոլորը"]],
            buttons: ["copy", "excel", "pdf", "colvis"],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Armenian.json"
            }
        }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#row_callback").DataTable({
            createdRow: function (t, a, e) {
                15e4 < 1 * a[2].replace(/[\$,]/g, "") && $("td", t).eq(2).addClass("highlight")
            }
        })
	})
</script>
@stop
