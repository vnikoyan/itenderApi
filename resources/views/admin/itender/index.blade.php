@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') itender տենդերներ @stop
@section('breadcrumb_active') տենդերներ @stop
@section('page_title') itender տենդերներ  @stop
@section('content')
<style>
   .userInfo{
      display: block;
      text-align: center;
   }
   .dis{
      display: block;
      font-size: 11px;
   }
   .table td{
      font-size: 12px;
   }
</style>
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <div class="table-responsive">
               <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                  <thead>
                     <tr>
                        <th>Ծածկագիր</th>
                        <th>Անվանումը</th>
                        <th>Կազմակերպիչ</th>
                        <th>Ստեղծման օր, ժամ	</th>
                        <th>Վերջնաժամկետ</th>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog" role="document" style="width: 95%; margin: 0 auto;max-width: 100%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Մանրամասներ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
         <table class="table table-striped  table-bordered dt-responsive dt-responsive nowrap">
            <thead>
               <tr>
                  <th>Չ/Հ</th>
                  <th>Անվանումը</th>
                  <th>Տեխնիկական բնութագիր</th>
                  <th>Չափման միավոր	</th>
                  <th>Քանակ</th>
                  {{-- <th>Գնման պայմանները</th>
                  <th>Վճարման պայմանները</th> --}}
                  <th>Նկար</th>
                  {{-- <th>Չափաբաժնի առավելագույն գին</th>
                  <th>Նվազագույն քայլ</th>
                  <th>Գնի նվազագույն թույլատրելի չափ</th> --}}
               </tr>
            </thead>
            <tbody id="appaedTboody">

            </tbody>
         </table>
      </div>
      <div class="modal-footer">
        <button type="button" style="width: 8%;float:left;" class="btn btn-secondary" data-dismiss="modal">Փակել</button>
      </div>
    </div>
  </div> 
</div>
<div class="modal fade" id="rejectedModal" tabindex="-1" role="dialog" aria-labelledby="rejectedModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectedModalLabel">Մերժել</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body table-responsive">
        <textarea id="rejectedText" name="rejected" style="width: 100%;" rows="4" cols="50"> </textarea>
      </div>
      <div class="modal-footer">
        <button id="modalSubmitRejected" data-id="" class="btn btn-xs btn-primary"><i class="fa fa-check"></i></button> 
        <button type="button"  class="btn btn-secondary" data-dismiss="modal">Փակել</button>
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
            serverSide: true,
            processing: true,
            ajax: '/admin/itender/tableData/{{$type}}',
            columns: [
               { data: 'code', name: 'code'},
               { data: 'name', name: 'name'},
               { data: 'user_id', name: 'user_id',    render:function (data, type, full, meta) {
                  if(full.user){
                     html  =  `<span class="userInfo">«${full.user.organisation.name.hy}» ${full.user.organisation.company_type.hy}</span>`;
                     html  +=  '<span class="userInfo">'+full.user.organisation.phone+'</span>';
                     html  +=  '<span class="userInfo">'+full.user.email+'</span>';
                     return html;
                  }
                  return "";
               }},
               { data: 'updated_at', name: 'updated_at'},
               { data: 'opening_date_time', name: 'opening_date_time'},
               { data: 'action', name: 'action'},
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
   
   $(document).on("click", "#modalSubmitRejected",function (event) {
      this_ = $(this);
      href = "/admin/itender/rejected/"+this_.data("id");
      rejected = $("#rejectedText").val();
         Swal.fire({
            title: "Համոզվա՞ծ ես",
            text: "Դուք չեք կարողանա վերադարձնել դա",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "$success",
            cancelButtonColor: "$danger",
            confirmButtonText: "Այո",
            cancelButtonText: "Չեղարկել",
         }).then(function (t) {
            if(t.value){
               $.ajax({
                  type: "POST",
                  headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  url: href,
                  dataType: "json", 
                  data:{rejected:rejected},
                  success: function (data) {
                     if(data.status){
                           t.value && Swal.fire("ՋՆՋՎԱԾ Է!", "Հաջողությամբ մերժվեց", "success");
                           $('#rejectedModal').modal("hide");
                           $('#exampleModal').modal("hide");
                           $("#rejectedText").val("");
                           $("#id__"+this_.data("id")).parents("tr").remove();
                     }else{
                           Swal.fire({ icon: "error", title: "Oops...", text: "Ինչ որ բան այնպես չգնաց!", footer: "" });
                     }
                  },
                  error:function(){
                     Swal.fire({ icon: "error", title: "Oops...", text: "Ինչ որ բան այնպես չգնաց!", footer: "" });
                  }
               });
            }
         });
   })

   $(document).on("click", ".cancelAndDeleteItem",function (event) {
      event.preventDefault();
      href = $(this).data("href");
      remove = $(this).data("remove");
      this_ = $(this);
      Swal.fire({
            title: "Համոզվա՞ծ ես",
            text: "Դուք չեք կարողանա վերադարձնել դա",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#ef4d56",
            cancelButtonColor: "$danger",
            confirmButtonText: "Մերժել",
            cancelButtonText: "Ջնջել",
      }).then(function (t) {
            if(t.value){
               $('#modalSubmitRejected').data("id",this_.data("id"));
               $('#rejectedModal').modal("show");
            }else if(t.dismiss == "cancel") {
               Swal.fire({
                     title: "Համոզվա՞ծ ես",
                     text: "Դուք չեք կարողանա վերադարձնել դա",
                     type: "warning",
                     showCancelButton: !0,
                     confirmButtonColor: "$success",
                     cancelButtonColor: "$danger",
                     confirmButtonText: "Այո",
                     cancelButtonText: "Չեղարկել",
                  }).then(function (t) {
                     if(t.value){
                           $.ajax({
                              type: "GET",
                              url: href,
                              dataType: "json", 
                              success: function (data) {
                                 if(data.status){
                                       this_.parents("tr").remove();
                                       if (remove != null){
                                          this_.parents(remove).remove();
                                       }
                                       t.value && Swal.fire("ՋՆՋՎԱԾ Է!", "Հաջողությամբ ջնջվել է.", "success");
                                 }else{
                                       Swal.fire({ icon: "error", title: "Oops...", text: "Ինչ որ բան այնպես չգնաց!", footer: "" });
                                 }
                              },
                              error:function(){
                                 Swal.fire({ icon: "error", title: "Oops...", text: "Ինչ որ բան այնպես չգնաց!", footer: "" });
                              }
                           });
                     }
                  });
            }
      });
      return false;

   });

   $(document).on("click", ".viewTneder",function (event) {
      event.preventDefault();
      id = $(this).data("id");
      $.ajax({
         url: '/admin/itender/getByid',
         type: 'GET',
         dataType: 'json',
         data: {id: id},
         success: function(data) {
            $('#exampleModal').modal("show");
            $("#appaedTboody").html("");
            $("#modalCheck").attr("href","/admin/itender/"+id+"/check");
            $("#modalDelete").data("href","/admin/itender/delete/"+id);
            $("#modalDelete").data("id",id);
            html = "";
            $.each(data, function(i, item) {
               html +=  "<tr>"; 
                  html +=  "<td>"+item.view_id+"</td>"; 
                  html +=  "<td>"+item.cpv.name+"</td>"; 
                  html +=  "<td>"+item.procurement_plan.specifications.description.hy+"</td>"; 
                  html +=  "<td>"+item.procurement_plan.unit+"</td>"; 
                  html +=  "<td>"+item.count+"</td>"; 
                  html +=  "<td>"+'-'+"</td>"; 
               html +=  "</tr>"; 
            });
            $("#appaedTboody").html(html);
         }
      })
   }); 

   

   
</script>
@stop