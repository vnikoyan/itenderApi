
<style>
    .cpov_list_section{
        display: none;
    }
</style>
@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Չափման միավորներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Ավելացնել Cpv @stop

@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                        <form action="{{ route('cpv.manualAddCpv') }}" method="post">
                                            @csrf
                                            <div class="form-group row">
                                                <div class="container">
                                                    @if(session()->has('message'))
                                                        <div class="alert alert-success">
                                                            {{ session()->get('message') }}
                                                        </div>
                                                    @endif
                                                    <div class = "col-md-12 mb-2">
                                                        <label for="" class="mb-0">Փնտրել ծնող Cpv կոդը</label>
                                                        <input type="text" class ="form-control search-parent-cpv" >
                                                    </div>
                                                    <div class="col-md-12 cpov_list_section mb-2">
                                                        <label for="" class="mb-0">Ընտրել ծնող Cpv կոդը</label>
                                                        <select name="parent_id" class="form-control cpv_list" >
                                                            <option value="" selected disabled> -- Ընտրել -- </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label for="" class="mb-0">Անվանում</label>
                                                        <input type="text" class ="form-control" name="name" required>
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label for="" class="mb-0">Cpv կոդ</label>
                                                        <input type="text" class ="form-control" name = "code" required min="8">
                                                    </div>
                                                    <div class="col-md-12 mb-2">
                                                        <label for="" class="mb-0">չափման միավոր</label>
                                                        <input type="text" class ="form-control" name="unit" >
                                                    </div>
                                                    <div class="col-md-12 mb-2 mb-4">
                                                        <label for="" class="mb-0">Տեսակ</label>
                                                        <select type="text" class ="form-control" name="type"  required>
                                                            <option value="0" disabled selected>- Ընտրել --</option>
                                                            <option value="1">Ապրանքներ</option>
                                                            <option value="2">Ծառայություններ</option>
                                                            <option value="3">Աշխատանքներ</option>
                                                        </select>
                                                    </div>
                                                    <div class ="col-md-12">
                                                        <button class="btn btn-success">Ավելացնել</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>    
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
@stop
@section('scripts')
    <script>
        $(document).on("input", ".search-parent-cpv", function () {
            $(".cpov_list_section").show();
            var val = $(this).val().trim();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
            type: "POST",
            url: "/admin/search/cpv/parent",
            dataType: "json",
            data: {code:val},
            success: function(data){
                if(data.length > 0){
                    $(".cpv_list").empty();
                    for(i= 0;  i < data.length; i++){
                        $(".cpv_list").append("<option value='"+data[i]['id']+"'> "+data[i]['code']+' - '+data[i]['name']+"</option>")
                    }
                }else{
                    $(".cpv_list").empty();
                    $(".cpv_list").append("<option value=>Ոչ մի համընկնում</option>")
                }

            }
            });
        })
    </script>
@stop
