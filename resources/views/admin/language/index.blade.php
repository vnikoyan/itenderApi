@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Լեզու @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Լեզուների Ցանկ @stop
@section('content')
<link href="/assets/back/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
<link href="/assets/back/plugins/animate/animate.css" rel="stylesheet" type="text/css">
   <div class="row">
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               <div class="table-responsive mb-2">
                  <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                     <thead>
                        <tr>
                           <th data-class="expand"><i class="fa fa-fw fa-language"></i> Լեզու</th>
                           <th>Կոդ</th>
                           <th>Գործողություն</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($language as $value)
                              <tr>
                                 <td><span class="bfh-languages" data-language="{{ $value->code }}" data-flags="true"></span>{{ $value->name }}</td>
                                 <td><span class="bfh-languages" data-language="{{ $value->code }}" data-flags="true"></span>{{ $value->code }}</td>
                                 <td>
                                    @if($value->code != "hy")
                                    <a href="/admin/language/edit/{{ $value->id }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                    @endif
                                 </td>
                              </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <!-- end col -->
   </div>
<!--end row-->
@stop
@section('scripts')

@stop
