@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Օգտատերեր @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Օգտատերերի Ցանկ @stop
@section('content')

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="form-check mb-2">
               <input class="form-check-input" type="checkbox" value="true" id="notLoggedInUsers">
               <label class="form-check-label" for="notLoggedInUsers">
                  Կատեգորիա չընտրած օգտատերեր
               </label>
            </div>
            {{-- <a href="/admin/user/create" id="but_add" class="btn btn-primary float-right mb-5">Ստեղծել</a> --}}
            <div class="table-responsive mb-2">
               <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                     <tr>
                        <th>Անվանում</th>
                        <th>Էլ-հասցե</th>
                        <th>մուտքանուն</th>
                        <th>Հեռախոս</th>
                        <th>Կարգավիճակ</th>
                        <th>ՀՎՀՀ</th>
                        <th>Փաթեթ</th>
                        <th>Գրանցման ամսաթիվ</th>
                        <th>Բալանս</th>
                        <th>Կատեգորիա</th>
                        <th>Գործողություններ</th>
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

<div class="modal fade" id="catModal" tabindex="-1" role="dialog" aria-labelledby="catModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catModalLabel">Կատեգորիաներ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
         <table class="table table-striped  table-bordered dt-responsive dt-responsive nowrap">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Cpv</th>
                     <th>Անվանումը</th>
                  </tr>
               </thead>
               <tbody id="appaedTboody">

               </tbody>
            </table>
      </div>
      <div class="modal-header">
         <h5 class="modal-title" id="catModalLabel">Ֆիլտրներ</h5>
      </div>
      <div class="modal-body table-responsive">
         <table class="table table-striped  table-bordered dt-responsive dt-responsive nowrap">
               <thead>
                  <tr>
                     <th>Անվանումը</th>
                     <th>Ընտրված տարբերակը</th>
                  </tr>
               </thead>
               <tbody id="filtersTboody">

               </tbody>
            </table>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
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

<script>
   $(document).ready(function () {
      var table = $("#datatable-buttons").DataTable({
         processing: true,
         serverSide: true,
         'ajax': {
            'url': '/admin/user/tableData/{{$type}}',
            'data': function(data){
               var notLoggedInUsers = $('#notLoggedInUsers').is(":checked");
               data.notLoggedInUsers = notLoggedInUsers;
            }
         },
         columns: [
               { data:'name', render:function (data, type, full, meta) {
                  if(data != null){
                     return data.hy
                  }else{
                     return "";
                  }
               }, name:'userName' },
               { data: 'email', name: 'email' },
               { data: 'username', name: 'username' },
               { data: 'phone', name: 'phone' },

               { data: 'status', name: 'status' },
               { data: 'userTin', name: 'tin' },
               { data: 'packageName', name: 'packageName' },
               { data: 'created_at', name: 'created_at' },
               { data: 'balans', name: 'balans'},
               { data: function (data, type, full, meta){
                  return '<a href="#'+data+'" data-id="'+data.userId+`" class="btn btn-xs btn-${data.first_login ? 'info' : 'primary'} viewCategory" data-toggle="tooltip-custom" data-placement="top" title="Դիտել" data-original-title="Դիտել" data-trigger="hover" ><i class="fa fa-eye"></i></a>`;
               }},
               { data: 'action', name: 'action'}

         ],
         dom: 'Bflrtip',
         order: [[ 10, "desc" ]],
         "lengthMenu": [[10, 50,100, -1], [10, 50,100, "Բոլորը"]],
         buttons: ["excel", "pdf"],
         createdRow: function (row, data, dataIndex) {
            if(data.first_login){
               $(row).addClass('bg-light');
            }
         },
         language: {
               url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Armenian.json"
         }
      })

      $('#notLoggedInUsers').change(function (e) { 
         // const checked = $('#notLoggedInUsers').is(":checked")
         if ($(this).is(':checked')) {
            $.fn.dataTable.ext.search.push({notLoggedInUsers: true})
         } else {
            $.fn.dataTable.ext.search.pop()
         }
         table.draw()
      });
	})

   $(document).on("click",".viewCategory", function (e) {
         e.preventDefault();
         this_ = $(this);
         id = this_.data("id");
         $.ajax({
         url: '/admin/user/getCat',
         type: 'GET',
         dataType: 'json',
         data: {id: id},
         success: function(data) {
            $('#catModal').modal("show");
            html = "";
            for (var i = 0; i < data.categories.length ;  i++) {
               html +=  "<tr>";
                  html +=  "<td>"+(i+1)+"</td>";
                  html +=  "<td>"+data.categories[i]['code']+"</td>";
                  html +=  "<td>"+data.categories[i].cpvName+"</td>";
               html +=  "</tr>";
            }
            $("#appaedTboody").html(html);

            if(data.filters != null){
               var region = (data.filters.region != null ) ? data.filters.region.map((elem)=> elem.name).join(", ") : "" ;
               var procedure = (JSON.parse(data.filters.procedure)) ? JSON.parse(data.filters.procedure).map((elem)=> elem.name).join(", ") : "" ;
               var electronic = (JSON.parse(data.filters.isElectronic)) ? JSON.parse(data.filters.isElectronic).name : "";
               var guaranteed = (JSON.parse(data.filters.guaranteed)) ? JSON.parse(data.filters.guaranteed).name : "";
               var type = (JSON.parse(data.filters.type)) ? JSON.parse(data.filters.type).name : "";
               var status = (JSON.parse(data.filters.status)) ? JSON.parse(data.filters.status).name : "";
               var organizator = (JSON.parse(data.filters.organizator)) ? JSON.parse(data.filters.organizator).map((elem)=> elem.name).join(", ") : "";
            }else{
               var guaranteed = '';
               var procedure = '';
               var electronic = '';
               var organizator = '';
               var region = '';
               var status = '';
               var type = '';
            }
            html = "";
               html +=  "<tr>";
                  html +=  "<td>Պատվիրատու</td>";
                  html +=  "<td>"+organizator+"</td>";
               html +=  "</tr>";
               html +=  "<tr>";
                  html +=  "<td>Տարածաշրջան</td>";
                  html +=  "<td>"+region+"</td>";
               html +=  "</tr>";
               html +=  "<tr>";
                  html +=  "<td>երաշխիքով-առանց երաշխիք</td>";
                  html +=  "<td>"+guaranteed+"</td>";
               html +=  "</tr>";
               html +=  "<tr>";
                  html +=  "<td>Ընթացակարգ</td>";
                  html +=  "<td>"+procedure+"</td>";
               html +=  "</tr>";
               html +=  "<tr>";
                  html +=  "<td>էլեկտրոնային-թղթային</td>";
                  html +=  "<td>"+electronic+"</td>";
               html +=  "</tr>";
                html +=  "<tr>";
                  html +=  "<td>Կարգավիճակ</td>";
                  html +=  "<td>"+status+"</td>";
               html +=  "</tr>";
               html +=  "<tr>";
                  html +=  "<td>Տեսակ</td>";
                  html +=  "<td>"+type+"</td>";
               html +=  "</tr>";
               
            $("#filtersTboody").html(html);
            }
      })
   });



</script>
@stop
