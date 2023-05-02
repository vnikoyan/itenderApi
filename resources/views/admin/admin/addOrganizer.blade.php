@extends('admin.layout.main')
@section('content')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<div class="w-100">
    <div class="mx-auto">
        <div class="row">
            <div class="col-4">
                <form method ="POST" action="{{ route('admin.addOrganizer')}}">
                    @csrf
                    <div class="card dr-pro-pic">
                        <div class="card-body">
                            @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                            @endif
                            <div class="col-md-10 form-group">
                                <label  class="">Պատվիրատու</label>
                                <select class="form-control" name="organizer">
                                    <option value ="" selected="true" disabled>Ընտրել պատվիրատու</option>
                                    <option value ="1">Պետական</option>      
                                    <option value ="2">Մասնավոր</option>      
                                </select>
                                @error('organizer')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-10 form-group">
                                <label for="" class="">Անվանում </label>
                                <textarea  style = "resize: none;height:120px;"name="name" class="form-control" value="{{ old('emails') }}"></textarea>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-10 form-group">
                                <button class="btn btn-primary">Ավելացնել</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <table id="organizerTable" class="table table-striped table-bordered dt-responsive w-100" style ="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead style="text-align: center;">
                            <tr>                    
                                <th>համարը</th>
                                <th>անվանում</th>
                                <th>տեսակ</th>
                                <th>ջնջել</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('scripts')
<script type="text/javascript">
        $(document).ready(function () {
        $.ajaxSetup({
             headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
        $("#organizerTable").DataTable({
            pageLength : 10,
            lengthChange : false,
            processing: true,
            searching: false,
            serverSide: true,
            ajax: {
               url: "/admin/get/organizators/",
               type: "GET",
               dataType:"json",
               dataSrc: "data",
               data: {},
            },
            columns: [
                { data: 'id'},
                { data: 'name'},
                { data: function ( data, type, row, meta ) {
                       if(data.is_state == "1"){
                        name = "Պետական"
                    }else{

                        name = "Մասնավոր"
                    }
                    return name } },
                { data: function ( data, type, row, meta ) {
                    return '<a  href="/admin/delete/organizator/'+data.id+'"class="btn btn-xs btn-danger deleteParticipant" data-key= '+data.id+'><i class="fa fa-ban"></i></a>'; } },
            ],
            dom: 'flrtip',
            "lengthMenu": [[10, 50,100, -1], [10, 50,100, "Բոլորը"]],
            buttons: ["copy", "excel", "pdf", "colvis"],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Armenian.json"
            }
        })

    })
</script>
@stop