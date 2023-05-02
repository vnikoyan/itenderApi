@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Ադմին @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Ադմինի Ցանկ @stop

@section('content')
<!-- end page title end breadcrumb -->
<style>
    .activeUser{
        box-shadow: 0px 0px 20px 1px #657fe8;
    }
    .textS{
        width: 100%;
        word-break: break-all;
    }
</style>
<div class="row">
    <div class="col-lg-5">
        @foreach($adminList as $key => $value)
            <div class="col-lg-12 userblock ">
                <div class="card profile-card">
                    <div class="card-body p-0">
                        <div class="media p-3 align-items-center" style="display: flex;">
                            <div class="media-body  align-self-center">
                                <h5 class="pro-title textS"> {{$value->name}} </h5>
                                <p class="mb-1 text-muted textS">{{$value->email}}</p>
                            </div>
                            <div class="action-btn" data-id="{{$value->id}}" style="position: inherit!important;display: flex;">
                                @if($value->id != 1 && Auth::guard('admin')->user()->id == 1)
                                    <button  class="mr-1 btnActive btn btn-sm btn-soft-info edit" data-toggle="tooltip-custom" data-placement="top" title="Խմբագրել" data-original-title="Խմբագրել" data-trigger="hover"><i class="fas fa-pen"></i></button>
                                    <button  class="mr-1 btnActive btn btn-sm btn-soft-warning permission" data-toggle="tooltip-custom" data-placement="top" title="Իրավասություն" data-original-title="Իրավասություն" data-trigger="hover"><i class="fas fa-key"></i></button>
                                    <a data-href="/admin/admin/{{$value->id}}/delete" data-remove=".userblock" class="btn btn-sm btn-soft-danger dele deleteItem" data-toggle="tooltip-custom" data-placement="top" title="Ջնջել" data-original-title="Ջնջել" data-trigger="hover"><i class="far fa-trash-alt"></i></a>
                                @else
                                    <button  class="mr-1 btnActive btn btn-sm btn-soft-info eye" data-toggle="tooltip-custom" data-placement="top" title="Դիտել" data-original-title="Դիտել" data-trigger="hover"><i class="fas fa-eye"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-lg-7" id="subPage">

    </div>
</div>
<div class="row">

</div>
<!--end row-->
@stop
@section('scripts')
<script>
    $(".btnActive").on("click", function (e) {
        $(".activeUser").removeClass("activeUser");
        $(this).parents(".userblock").addClass("activeUser");
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    });

    $(".edit").on("click", function (e) {
        id = $(this).parents(".action-btn").data("id");
        $( "#subPage" ).load("/admin/admin/"+id+"/edit", function() {

        });
    });
    $(".eye").on("click", function (e) {
        id = $(this).parents(".action-btn").data("id");
        $( "#subPage" ).load("/admin/admin/"+id+"", function() {

        });
    });
    $(".permission").on("click", function (e) {
        id = $(this).parents(".action-btn").data("id");
        $( "#subPage" ).load("/admin/admin/getPermission/"+id, function() {

        });
    });
    $(document).on("submit", ".adminForm",function(e){
        e.preventDefault();
        $("#error_name").text("");
        $("#error_user_name").text("");
        $("#error_email").text("");
        $("#error_password").text("");
        $("#error_password_confirmation").text("");
        url = $(this).attr("action");
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $(".adminForm").serialize(),
            success: function(data){
                if(data.status == false){
                    $.each( data.errors, function( key, value ) {
                        $("#error_"+key).text(value[0]);
                    });
                }else{
                    location.reload();
                }
            }
         })
    })

</script>
@stop
