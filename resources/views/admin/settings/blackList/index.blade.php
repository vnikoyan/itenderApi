@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Սև ցուցակ @stop
@section('breadcrumb_active') Դիտել @stop
@section('page_title') Սև ցուցակ  @stop
@section('content')

<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<div class="row">
   <div class="col-8">
      <div class="card">
         <div class="card-body">
            <a href="/admin/black_lists/create" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a>
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                     <tr>
                        <th>Հերթական համար</th>
                        <th>ՀՎՀՀ</th>
                     </tr>
                  </thead>
                  <tbody></tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-4">
      <div class="card">
         <div class="card-body">
            {{ Form::model($blackList,array('route' => array('black_lists.fileUploade'),'class'=>'form-horizontal form-material mb-0','files' => true))}}
            @method('POST')
               <div class="form-group row">
                  <div class="col-xl-12">
                     <div class="card">
                           <div class="card-body">
                              <h4 class="mt-0 header-title"> Կցել ֆայլ </h4>
                              <input type="file" id="input-file-now-custom-2" name="file" class="dropify" >
                              <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('file') }} </p>
                           </div>
                     </div>
                  </div>
               </div>
               <div class="form-group">
                     <a href="/admin/blackList"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                     <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                  </div>
            {{ Form::close() }}

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
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#datatable-buttons").DataTable({
            processing: true,
            serverSide: true,
            ajax: '/admin/black_lists/tableData',
            columns: [
                { data: 'id', name: 'id'},
                { data: 'name', name: 'name'},
            ],
            dom: 'flrtip',
            "lengthMenu": [[10, 50,100, -1], [10, 50,100, "Բոլորը"]],
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
