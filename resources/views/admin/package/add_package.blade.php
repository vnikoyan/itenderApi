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
                                       <form method = "POST" action="{{ route('admin.user.addOrder') }}" class="form-horizontal form-material mb-0">
                                            @csrf
                                            <div class="col-md-10 form-group">
                                                <label for="package" class="">Փաթեթ</label>
                                                <select class="form-control" id ="package-sel" name ="package">
                                                    <option value = "" selected="true" disabled="true">Ընտրել փաթեթ</option>
                                                    @foreach($packages as $ps)
                                                        <option value="{{ $ps->id}}" data-key="{{$ps->name}}" > {{ $ps->name }} </option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('package'))
                                                    <div class="error">{{ $errors->first('package') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <label for="month" class="">արժեքը</label>
                                                <select class="form-control" id ="package-price" name ="price">
                                                     <option value = "" selected="true" disabled="true">Ընտրել արժեքը</option>
                                                    @foreach($packages as $ps)
                                                        <option value="{{ $ps->price_1 }}" data-key="{{$ps->name}}"  style = "display: none;"data-m="1" > {{ $ps->price_1 }} դրամ</option>
                                                        <option value="{{ $ps->price_3 }}" data-key="{{$ps->name}}"  style = "display: none;"data-m="3"> {{ $ps->price_3 }} դրամ</option>
                                                        <option value="{{ $ps->price_6 }}" data-key="{{$ps->name}}"  style = "display: none;"data-m="6"> {{ $ps->price_6 }} դրամ</option>
                                                        <option value="{{  $ps->price_12 }}" data-key="{{$ps->name}}"  style = "display: none;"data-m="12"> {{ $ps->price_12 }} դրամ</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="month">
                                               @if($errors->has('price'))
                                                    <div class="error">{{ $errors->first('price') }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-10 form-group">
                                                <label for="search-user" class="">Փնտրել ըստ ՀՎՀՀ-ի, էլ․հասցեի կամ անվանմամբ</label>
                                                <input type="texts" name="search" id="search-user" class="form-control">
                                                @if($errors->has('search'))
                                                    <div class="error">{{ $errors->first('search') }}</div>
                                                @endif
                                                <div class="form-group">
                                                    <select class="search-result form-control" style ="display: none;">
                                                        
                                                    </select>
                                                </div>
                                                <input type="hidden" name="search-key">
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


  $("#package-sel").change(function(){

    var key = $('#package-sel').find(":selected").attr("data-key");
        $( "#package-price option" ).each(function() {
            if( $(this).attr("data-key") == key){
                $(this).show();
            }else{
                $(this).hide();
            }
        })
    })

  $("#package-price").change(function(){
    var month = $( "#package-price option:selected" ).attr("data-m");
    console.log(month)
    $("input[name=month]").val(month);
  })

    $(document).on("input", "#search-user", function () {
        var val = $(this).val().trim();
        if(val != ""){
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
               }
            });
            $.ajax({
                url: "/admin/order/search/user",
                type: "POST",
                data: {val : val},
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
       $("#search-user").val(text);
       $("input[name=search-key]").val(val);
       $(".search-result").hide();
    })

</script>
@stop
