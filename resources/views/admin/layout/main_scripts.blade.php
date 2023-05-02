<script src="{{asset('/assets/back/assets/js/jquery.min.js')}}"></script>
<script>
    $(document).on("click", ".parentMenu",function (event) {
        event.preventDefault();
        id = $(this).attr("href");
        window.location.href = $(id +" .nav").eq(0).children(".nav-item").eq(0).children("a").attr("href");
        return false;
    });
</script>
<script src="{{asset('/assets/back/assets/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/js/metismenu.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/js/waves.js')}}"></script>
<script src="{{asset('/assets/back/assets/js/feather.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/js/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.crm_leads.init.js')}}"></script>

<script src="{{asset('/assets/back/plugins/sweet-alert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.sweet-alert.init.js?v=1')}}"></script>
<script src="{{asset('/assets/back/plugins/moment/moment.js')}}"></script>
<script src="{{asset('/assets/back/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/assets/back/plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/timepicker/bootstrap-material-datetimepicker.js')}}"></script>
<script src="{{asset('/assets/back/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('/assets/back/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.forms-advanced.js')}}"></script>

<script src="{{asset('/assets/back/assets/js/app.js?12')}}"></script>
<script>


    $(".main-icon-menu-pane").removeClass("active");
    $(".parentMenu").removeClass("active");
    $(".subMenuItem").children(".active").parents(".main-icon-menu-pane").addClass("active");
    $(".parentMenu[href='#"+$(".subMenu").children(".active").attr("id")+"']").addClass("active");
    

    $(document).on("click", ".warningLink",function (event) {
        event.preventDefault();
        text   = $(this).data("text");
        footer = $(this).data("footer");
        Swal.fire({ icon: "warning", title: "Oops...", text: text, footer: footer });
    });
    $(document).on("click", ".deleteItem",function (event) {
        event.preventDefault();
        href = $(this).data("href");
        remove = $(this).data("remove");
        this_ = $(this);
        Swal.fire({
            title: "Համոզված ես?",
            text: "Դուք չեք կարողանա վերադարձնել դա!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "$success",
            cancelButtonColor: "$danger",
            confirmButtonText: "Այո!",
            cancelButtonText: "Չեղարկել!",
        }).then(function (t) {
            if(t.value){
                $.ajax({
                    type: "GET",
                    url: href,
                    dataType: "json", 
                    success: function (data) {
                        if(data.status){

                            this_.parents("tr").remove();
                            location.reload();
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
        return false;

    });

    

    $(".startDataTime").change();
    $(".endDataTime").change();

</script>