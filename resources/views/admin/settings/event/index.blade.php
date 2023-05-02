@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Նորություններ @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Նորությունների Ցանկ @stop
@section('content')
<div class="row">
                  <div class="col-12">
                     <div class="card">
                        <div class="card-body">
                        <a href="/admin/event/create" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a>
                           <div class="table-responsive">
                              <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                 <thead>
                                    <tr>
                                       <th>Նկար</th>
                                       <th>Անվանում</th>
                                       <th>Action</th>
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
            ajax: '/admin/event/tableData',
            columns: [
               { data: 'image', name: 'image',    render:function (data, type, full, meta) {
                  return '<img src="/uploads/event/'+data+'" class="img-rounded img-preview" style="width: 100px; margin: 0 auto; display: block; ">';
               }},
                { data: 'title', name: 'title'},
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            dom: 'lrtip',
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
