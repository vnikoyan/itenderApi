{{ Form::model($admin,array('route' => array('admin.admin.updatePermission',$admin->id),'class'=>'form-horizontal form-material mb-0'))}}
@method('PUT')
<div class="form-group row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive attendance-table">
                            <table class="table table-bordered mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th>Գործառույթ</th>
                                        <th>Իրավասություն</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission as $key => $value)
                                        <tr>
                                            <td>{{$key}}</td>
                                            @foreach($value as $k => $val)
                                                @if(isset($adminPermissions[$key][$k]) && $adminPermissions[$key][$k]->id == $val->id)
                                                    <td>{{ Form::checkbox('permission['.$val->id.']', "1", 1,["class"=>""])}}</td>
                                                @else
                                                    <td>{{ Form::checkbox('permission['.$val->id.']', "1", 0,["class"=>""])}}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end /table-->
                        </div>
                        <!--end /tableresponsive-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <a href="/admin/admin"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
    <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
</div>

{{ Form::close() }}