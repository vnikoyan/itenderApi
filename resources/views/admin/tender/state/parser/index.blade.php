@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Մրցույթների Հայտարարությունների Ցանկ @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Մրցույթների Հայտարարությունների Ցանկ @stop
@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">
            <input id="parserType" name="parserType" type="hidden" value="{{ $type }}">
            <table class="table table-striped dt-responsive typeTabel procurementAnnouncements" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
               <thead>
                  <tr style="font-size: 12px;">
                     <th>
                        <p title="Ջնջել" class="btn btn-xs btn-danger remove-parsers mb-0 -mt-0 text-center">
                           <i class="fa fa-ban"></i>
                        </p>
                     </th>
                     <th>
                        <p class="btn btn-xs btn-success mb-0 mt-0 check-all" title="Նշել բոլորը">
                           <i class="fa fa-check"></i>
                        </p>
                     </th>
                     <th>Վերնագիր</th>
                     <th>Սկիզբ</th>
                     <th>Ավարտ</th>
                     <th>Ֆայլ</th>
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
<script>
    $(document).ready(function () {
        $(".procurementAnnouncements").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
               url: "/admin/tender_state_parser/get/not/approved",
               type: "POST",
               dataType:"json",
               dataSrc: "data",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                 parserType: $("#parserType").val(),
               },
            },
            columns: [
               { data: 'action', name: 'action'},
               { data: 'id'},
               { data: 'title'},
               { data: 'start_date'},
               { data: 'end_date'},
               { data: function ( data, type, row, meta ) {
                           return '<a class="btn btn-xs btn-warning" href="'+data['link']+'" download ><i class="fa fa-file"></i></a>'; } },
               { data: function(data, type, row, meta){
                  if(data.published == 1){
                     return '<p style = "color:#ff9f42;font-weight: 600;text-align: center;">Հրապարակված է </p>';
                  }else{
                     var id = data['id'].split("ID: ")[1];
                     return '<div class="row"><a href="/admin/tender_state_parser/edit/'+id+'" class="btn btn-xs btn-primary mr-2"><i class="fa fa-edit"></i>Խմբագրել</a><a href="/admin/tender_state_parser/delete/'+id+'" data-tablename="userTable"  title="Ջնջել" class="btn btn-xs btn-danger waves-effect waves-light"><i class="fa fa-ban"></i></a></div>';
                  }
               }},
            ],
            dom: 'flrtip',
            order: [[ 3, "desc" ]],
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

   $(".remove-parsers").click(function(event){
      event.stopPropagation();
      var parserIdS = [];
      $('.parser_ids').each(function(){
         if($(this).is(':checked')){
            parserIdS.push($(this).val())
         };
      })
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(parserIdS.length > 0){
            $.ajax({
               type: "POST",
               url: "/admin/remove/parsers/by/id",
                dataType: "json",
               data: {parserIdS:parserIdS},
               success: function(data){
                  location.reload(true);
               }
            })
         }
   })

   $(".check-all").click(function(event){
         event.stopPropagation();
         $('.parser_ids').attr("checked","checked");
      })
</script>
<style>
   .typeTabel td{
      font-size: 14px;
       padding: 5px!important;
   }
   .parser_ids{
      margin-left: 20px;
   }
   .remove-parsers{
      padding: 2px 8px;
   }
   .check-all{
      display: inline-block;
      padding: 2px 8px;
      margin-left: 5px;
   }
</style>
@stop
