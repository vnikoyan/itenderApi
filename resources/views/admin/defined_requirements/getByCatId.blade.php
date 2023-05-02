<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                    <a htef="#" class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0 addDefinedRequirements">Ստեղծել</a>
                    <br>
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Անվանում</th>
                                <th>Հերթականություն</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($defined_requirementsList as $key => $value)
                            <tr>
                                <td>{{$value->title}}</td>
                                <td>{{$value->order}}</td>
                                <td>
                                    <a data-toggle="modal" data-target="#myDefinedRequirements_{{$value->id}}" href="#" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>  
                                    <a href="#" data-tablename="userTable" data-href="/admin/defined_requirements/delete/1" class="btn btn-xs btn-danger waves-effect waves-light deleteItem"><i class="fa fa-trash"></i> Ջնջել</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>

@foreach($defined_requirementsList as $key => $value)
<div class="modal fade" id="myDefinedRequirements_{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Սահմանվող պահանջներ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::model($value,array('route' => array('defined_requirements.update',$value->id),'class'=>'form-horizontal form-material mb-0'))}}
      @method('PUT')
      <input type="hidden" name="cpv_id" value="{{$value->cpv_id}}">
      <div class="modal-body table-responsive">
        <div class="form-group row">
            @foreach($language as $ke => $va)
                <div class="col-md-3">
                    <label for="" class="">Անվանում {{$va->name}}</label>
                    {{ Form::text('title['.$va->code.']', $value->getTranslation("title",$va->code), ['class'=>'form-control','required'=>'required',"placeholder"=>"Նկարագրություն "]) }}
                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title.'.$va->code) }} </p>
                </div>
            @endforeach
            <div class="col-md-3">
                <label for="" class="">Հերթականություն</label>
                {{ Form::number('order', $value->order, ['class'=>'form-control','required'=>'required',"placeholder"=>"Հերթականություն"]) }}
                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
            </div>
            <h5 class="modal-title" style="padding: 0 1rem;">Արժեքներ</h5>
            
            <?php $valueOrder = json_decode($value->valueOrder,true); ?>
            <div class="col-md-12">
               <a href="#" class="btn btn-secondary pluse" style="padding: 0 1rem;">+</a>
            </div>
            <div class="col-md-12 minusPlus">
                    @foreach(json_decode($value->value,true) as $ke => $va)
                    @if($ke == 0)
                        <div class="row">
                    @else
                        <div class="row removeRow">
                    @endif
                        <div class="col-md-5">
                            <label for="" >Արժեքներ</label>
                            {{ Form::text('value[]', $va, ['class'=>'form-control','required'=>'required',"placeholder"=>"Արժեքներ"]) }}
                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('value') }} </p>
                        </div>
                        <div class="col-md-5">
                            <label for="" class="">Հերթականություն</label>
                            {{ Form::number('valueOrder[]', $valueOrder[$ke], ['class'=>'form-control','required'=>'required',"placeholder"=>"Հերթականություն"]) }}
                            <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('valueOrder') }} </p>
                        </div>
                        @if($ke != 0)
                            <div class="col-md-2">
                                <label for="" class="col-md-12">Ջնջել</label>
                                <a href="#" class="btn btn-secondary minus" style="padding: 0 1rem;"> - </a>
                            </div>
                        @endif

                </div>

                    @endforeach
            </div>

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