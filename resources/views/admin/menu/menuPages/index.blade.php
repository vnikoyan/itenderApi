@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') Մենյու միավորներ @stop
@section('breadcrumb_active') Խմբագրել @stop
@section('page_title') Մենյու միավորներ  @stop

@section('content')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card dr-pro-pic">
                <div class="card-body">
                    <div class="">
                    <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>title</th>   
                                    <th>slug</th>   
                                    <th>View</th>   
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($page as $value)
                                <tr class="smart-form" id="{{ $value->id}}" >
                                    <td>{{$value->title}}</td>
                                    <td>{{$value->slug}}</td>
                                    <td>
                                    @if($menuPages->isPageChecked(['page_id'=>$value->id,"menu_id"=>$id]))
                                         <section class="col col-5">                
                                            <label class="toggle"><input type="checkbox" class="checkboxA" data-menu="{{$id}}" data-page="{{$value->id}}" value="1" checked><i data-swchon-text="ON" data-swchoff-text="OFF"></i></label>
                                         </section>
                                    @else
                                    <section class="col col-5">                
                                       <label class="toggle"><input type="checkbox" data-menu="{{$id}}" data-page="{{$value->id}}"  class="checkboxA" value="1" ><i data-swchon-text="ON" data-swchoff-text="OFF"></i></label>
                                    </section>
                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <button type="button" class="btn btn-primary checkboxSave">Save</button>
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
        $(document).ready(function () {

            function toObject(arr) {
              var rv = {};
              for (var i = 0; i < arr.length; ++i)
                if (arr[i] !== undefined) rv[i] = arr[i];
              return rv;
            }
            $(".checkboxSave").on('click',function(event) {
               json = [];
                $( ".checkboxA" ).each(function( key,index ) {
                      checked = $(this).is(':checked');
                      json[key] = [];
                     json[key]["1"] = checked;
                        page  =  $(this).data("page");
                     json[key]["2"] = page;
                     json[key] = toObject(json[key])
                });

                $.ajax({
                    url: '/admin/MenuPages/isPageCheckedSave',
                    type: 'GET',
                    data: {isChecked: toObject(json),menu:$( ".checkboxA" ).data("menu") },
                    success: function (data) {
                         Swal.fire("Պահպանված Է!", "Հաջողությամբ պահպանված է.", "success");
                    }
                })
            });
        })
    </script>
@stop