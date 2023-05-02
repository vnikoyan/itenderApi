@extends('admin.layout.main')   
@section('content')
                    <!-- end page title end breadcrumb -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card dr-pro-pic">
                                <div class="card-body">
                                    <div class="">
                                        @if (\Session::has('success'))
                                            <div class="alert alert-success">
                                                <ul>
                                                    <li>{!! \Session::get('success') !!}</li>
                                                </ul>
                                            </div>
                                        @endif
                                       <form method = "POST" action="{{ route('admin.user.addPackageState') }}" class="form-horizontal form-material mb-0">
                                            @csrf
                                            <div class="col-md-10 form-group">
                                                <label for="package" class="">Փաթեթ</label>
                                                <select class="form-control" id ="package" name ="package">
                                                    <option value = "" selected="true" disabled="true">Ընտրել փաթեթ</option>
                                                    <option value = "1">Մեկ անձ</option>
                                                    <option value = "2">Մրցակցային</option>
                                                </select>
                                                @if($errors->has('package'))
                                                    <div class="error">{{ $errors->first('package') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <label for="month" class="">արժեքը</label>
                                                <select class="form-control" id ="price" name ="price">
                                                     <option value = "" selected="true" disabled="true">Ընտրել արժեքը</option>
                                                    @foreach($packagesState as $ps)
                                                        <option value="{{ $ps->id}}" data-key="{{ $ps->type}}" style ="display: none;"> {{ $ps->price }} դրամ</option>
                                                    @endforeach
                                                </select>
                                               @if($errors->has('price'))
                                                    <div class="error">{{ $errors->first('price') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <label for="tin" class="">Փնտրել ըստ անվանումի</label>
                                                <input type="text" name="name" id="tin" class="form-control">
                                                @if($errors->has('name'))
                                                    <div class="error">{{ $errors->first('name') }}</div>
                                                @endif
                                                <input type="hidden" name="og-id">
                                                <div class="form-group">
                                                    <select class="search-result form-control" style ="display: none;"></select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Ավելացնել</button>
                                            </div>
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
@stop
@section('scripts')
<script type="text/javascript">
    $(document).on("input", "#tin", function () {
        var name = $(this).val().trim();
        if(name != ""){
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               }
            });
            $.ajax({
                url: "/admin/search/by/name",
                type: "POST",
                data: {name : name},
                dataType: "Json",
                success: function(data) {
                    $(".search-result").empty();
                    $(".search-result").show();
                    $(".search-result").append("<option value ='0'>-- Տեսնել բոլորը --</option>")
                    for(var i = 0; i < data.length; i++){
                        $(".search-result").append("<option value ="+data[i].id+">"+data[i].name.hy+"</option>")
                    } 
                }
            });
        }
    })

    $(".search-result").change(function(){
       var text =  $( ".search-result option:selected" ).text();
       var val =  $( ".search-result option:selected" ).val();
       $("#tin").val(text);
       $("input[name=og-id]").val(val);
       $(".search-result").hide();
    })

    $("#package").change(function(){
        var val = $( "#package option:selected" ).val();
        $( "#price option").show();
            if(val == "2"){
                $( "#price option" ).each(function( ) {
                    if($(this).attr("data-key") == "ONE_PERSON"){
                        $(this).hide();
                    }
                });
            }
            if(val == "1"){
                $( "#price option" ).each(function( ) {
                    if($(this).attr("data-key") == "COMPETITIVE"){
                        $(this).hide();
                    }
                });
            }
    })
</script>
@stop
