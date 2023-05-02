@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Հայտարարություններ @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Հայտարարությունների Ցանկ @stop
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            @if($type == 4)
               <a href="/admin/tender_state/create/4?type={{$type}}" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a>
            @else
               <a href="/admin/tender_state/create?type={{$type}}" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a>
            @endif

            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                     <tr>
                        <th>Ծածկագիր</th>
                        <th>Վերնագիր</th>
                        <th>Պատվիրատու</th>
                        <th>Սկիզբ</th>
                        <th>Ավարտ</th>
                        <th>Նախահաշվային գին</th>
                        <th>Հրավեր</th>
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
            ajax: '/admin/tender_state/tableData/{{$type}}',
            columns: [
               { data: 'password', name: 'password', searchable: true},
               { data: 'title', name: 'title', searchable: true},
               { title: 'Պատվիրատու', render: function(datum, type, row) {
                  if(row.organizator){
                     return row.organizator.name;
                  } else {
                     return row.customer_name
                  }
               }},
               { data: 'start_date', name: 'start_date'},
               { data: 'end_date', name: 'end_date'},
               { title: "Նախահաշվային գին", render: function(datum, type, row) {
                  return row.estimated_file ? '<a href="'+row.estimated_file+'" target="_blank" class="btn btn-xs btn-primary" data-toggle="tooltip-custom" data-placement="right" title="Ներբեռնել" data-original-title="Ներբեռնել" data-trigger="hover" download><i class="fa fa-file"></i></a>' : '';
               }},
               { title: "Հրավեր", render: function(datum, type, row) {
                  return row.invitation_link ? '<a href="'+row.invitation_link+'" target="_blank" class="btn btn-xs btn-primary" data-toggle="tooltip-custom" data-placement="right" title="Ներբեռնել" data-original-title="Ներբեռնել" data-trigger="hover" download><i class="fa fa-file"></i></a>' : '';
               }},
               { title: "Գործողություն", render: function(datum, type, row) {
                  return `<a href="/admin/tender_state/${row.id}/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Խմբագրել</a>  
                     <a href="#" data-tableName="userTable" data-href="/admin/tender_state/delete/${row.id}"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>`;
               }},
            ],
            order: [[ 0, "desc" ]],
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
