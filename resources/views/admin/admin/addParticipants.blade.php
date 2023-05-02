@extends('admin.layout.main')
@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="row">
            <div class="col-lg-6">
            <form method ="POST" action="{{ route('admin.addNewParticipants')}}">
                @csrf
                <div class="card dr-pro-pic">
                <div class="card-body">
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <div class="col-md-10 form-group">
                        <label  class="">Կատեգորիա</label>
                        <select class="select2 form-control" name="category">
                            <option value ="0">-Բոլորին-</option>
                            @foreach($categories as $category)
                                <option value = {{$category->id}}>{{ $category->code .' - '.$category->name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-10 form-group">
                        <label for="" class="">Այլ ստացողներ </label>
                        <textarea  style = "resize: none;height:120px;"name="emails" class="form-control" value="{{ old('emails') }}" placeholder="Խնդրում ենք էլ․ հասցեները իրարից առանձնացնել ստորակետերով"></textarea>
                        @error('emails')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-10 form-group">
                        <button class="btn btn-primary">Ավելացնել</button>
                    </div>
                </div>
        </form>
        </div>
            </div>
            <div class="col-lg-4">
                <table id = "participantTable" class="table table-striped table-bordered dt-responsive nowrap" style ="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead style="text-align: center;">
                    <tr>                    
                        <th>Քանակ</th>
                        <th>Email</th>
                        <th>ջնջել</th>
                    </tr>
                    </thead>
                </table>
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
        $("#participantTable").DataTable({
            pageLength : 10,
            lengthChange : false,
            processing: true,
            searching: false,
            serverSide: true,
            ajax: {
               url: "/admin/get/participants",
               type: "POST",
               dataType:"json",
               dataSrc: "data",
               data: {},
            },
            columns: [
                { data: 'id'},
                { data: 'email'},
                { data: function ( data, type, row, meta ) {
                    return '<a  href="/admin/delete/participant/email/'+data.id+'"class="btn btn-xs btn-danger deleteParticipant" data-key= '+data.id+'><i class="fa fa-ban"></i></a>'; } },
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