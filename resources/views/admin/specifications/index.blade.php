<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
               <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0 addSpecifications">Ստեղծել</button>
                <div class="">
                    <table id="datatable123" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Նկարագրություն</th>
                                <th>հեղինակ</th>
                                <th>Օգտագործվել է</th>
                                <th>Քանի անգամ</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($specificationsList as $key => $value)
                            <tr>
                                <td>{!! $value->getTranslation("description","hy")!!}</td>
                                @if($value->user_id == 0)
                                    <td>Admin</td>
                                @else
                                    <td>{{$value->user->name}}</td>
                                @endif
                                <td>#</td>
                                <td>#</td>
                                <td>
                                 <a data-toggle="modal" data-target="#myEdits_{{$value->id}}" href="#" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a> 
                                 <a href="#" data-tablename="userTable" data-href="/admin/specifications/delete/{{$value->id}}"  class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($specificationsList as $key => $value)
<div class="modal fade" id="myEdits_{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Տեխ․ բնութագրեր</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::model($value,array('route' => array('specifications.update',$value->id),'class'=>'form-horizontal form-material mb-0'))}}
      @method('PUT')
      <input type="hidden" name="cpv_id" value="{{$value->cpv_id}}">
      <div class="modal-body table-responsive">
        <div class="form-group row">
            @foreach($language as $ke => $va)
                <div class="col-md-12">
                    <label for="" class="">Նկարագրություն {{$va->name}}</label>
                    {{ Form::textarea('description['.$va->code.']', $value->getTranslation("description",$va->code), ['class'=>'form-control','required'=>'required',"placeholder"=>"Նկարագրություն "]) }}
                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$va->code) }} </p>
                </div>
            @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button"  class="btn btn-secondary btn-sm text-light px-4 mt-3 float-right mb-0" data-dismiss="modal">Չեղարկել</button>
        <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
      </div>
      {{ Form::close() }}
    </div>
  </div> 
</div>
@endforeach