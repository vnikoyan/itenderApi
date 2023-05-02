@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Գործընկերներ @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Գործընկերների Ցանկ @stop
@section('content')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
   <div class="row">
      @foreach($coworkers as $key => $value)
         @if($value->user)
            <div class="col-lg-4 cardr">
               <div class="card">
                  <div class="card-body">
                        <div class="media mb-3">
                           <img src="/uploads/coWorkers/{{$value->image}}" class="mr-3 thumb-xl align-self-center rounded-circle" alt="...">
                           <div class="media-body align-self-center">
                              <h4 class="mt-0 mb-1 title-firm">{{$value->user->name}}</h4>
                           </div>
                           <!--end media body-->
                        </div>
                        <!--end media-->
                        <ul class="list-unstyled mb-2">
                           <li class="mb-2 line-clamp"><i data-feather="phone" class="align-self-center icon-sm icon-dual"></i> <b>Հեռախոսահամար </b>:&nbsp; {{$value->user->phone}}</li>
                           <li class="mb-2 line-clamp"><i data-feather="mail" class="align-self-center icon-sm icon-dual"></i> <b>Էլ․ փոստ </b>:&nbsp; {{$value->user->email}}</li>
                           <li class="mb-2 line-clamp"><i data-feather="map-pin" class="align-self-center icon-sm icon-dual"></i> <b>Հասցե</b> :&nbsp; {{$value->address}}</li>
                           <li class="line-clamp"><i data-feather="globe" class="align-self-center icon-sm icon-dual"></i> <b>Վեբ կայք</b> :&nbsp; {{$value->website}}</li>
                        </ul>
                        <p class="text-muted mt-3 website-info"><span class="text-dark font-weight-semibold">Գործունեության ոլորտը:&nbsp;</span>{{$value->cpv}}</p>
                        <div>
                           @if($value->status == 0 )
                           <a href="/admin/co_workers/editStatus/{{$value->id}}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Հաստատել</a>
                           @endif
                           <a href="/admin/co_workers/{{$value->id}}/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Խմբագրել</a>
                           <a href="#" data-href="/admin/co_workers/delete/{{$value->id}}" data-remove=".cardr" class="btn btn-xs btn-danger deleteItem"><i class="fa fa-trash"></i>Ջնջել</a>
                        </div>
                  </div>
                  <!--end card-body-->
               </div>
               <!--end card-->
            </div>
         @endif
      @endforeach
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

@stop
