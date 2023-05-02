<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                    {{ Form::model($specifications,array('route' => array('specifications.store'),'class'=>'form-horizontal form-material mb-0'))}}
                        <div class="form-group row">
                            @foreach($language as $key => $value)
                                <div class="col-md-12">
                                    <label for="" class="">Նկարագրություն {{$value->name}}</label>
                                    {!! Form::textarea('description['.$value->code.']', $specifications->getTranslation("description",$value->code), ['class'=>'form-control html' ,"placeholder"=>"Նկարագրություն "]) !!}
                                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$value->code) }} </p>
                                </div>  
                            @endforeach
                        </div>
                        <div class="form-group">
                            <a href="/admin/specifications"  class="btn btn-gradient-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Չեղարկել</a>
                            <button class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                        </div>
                        {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
</div>