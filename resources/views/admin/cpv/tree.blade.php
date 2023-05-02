@if($type == 1)
    @php $t = "Ապրանքներ"; @endphp
@elseif($type == 2)
    @php $t = "Ծառայություններ"; @endphp
@else
    @php $t = "Աշխատանքներ"; @endphp
@endif
@extends('admin.layout.main')
@section('breadcrumb_first') Itender @stop
@section('breadcrumb_second') cpv  @stop
@section('breadcrumb_active')  {{$t}}  @stop
@section('page_title') cpv  {{$t}} @stop
@section('content')
<style>
.jstree-anchor{
    display: inline-block;
    max-width: 97%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
<link href="/assets/back/plugins/treeview/themes/default/style.css" rel="stylesheet">
<div class="row">
    <div class="col-sm-6">
        <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{$t}}</h4>
            <div id="tree">
            </div>
        </div>
        <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <div class="btns">
                    <a class="btn btn-primary btn-sm text-light px-4 mt-3  mb-0 disabled defined_requirements">Սահմանվող պահանջներ</a>
                    <a class="btn btn-primary btn-sm text-light px-4 mt-3  mb-0 disabled specifications">Տեխ․ բնութագրեր</a>
                </div>
                <div class="appand" >
                </div>
                <!-- <iframe class="appandiframe" style=" width: 100%; border: 0; height: 100vw;display:none "></iframe> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Տեխ․ բնութագրեր</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::model($specifications,array('route' => array('specifications.store'),'class'=>'form-horizontal form-material mb-0'))}}
      <input type="hidden" name="cpv_id" id="cpv_id">
      <div class="modal-body table-responsive">
        <div class="form-group row">
            @foreach($language as $key => $value)
                <div class="col-md-12">
                    <label for="" class="">Նկարագրություն {{$value->name}}</label>
                    {!! Form::textarea('description['.$value->code.']', $specifications->getTranslation("description",$value->code), ['class'=>'form-control','required'=>'required',"placeholder"=>"Նկարագրություն "]) !!}
                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('description.'.$value->code) }} </p>
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

<div class="modal fade" id="addDefinedRequirements" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog-centered modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Տեխ․ բնութագրեր</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {{ Form::model($definedRequirements,array('route' => array('defined_requirements.store'),'class'=>'form-horizontal form-material mb-0'))}}
      <input type="hidden" name="cpv_id" id="cpv_id_definedRequirements">
      <div class="modal-body table-responsive">
        <div class="form-group row">
            @foreach($language as $ke => $va)
                <div class="col-md-3">
                    <label for="" class="">Անվանում {{$va->name}}</label>
                    {{ Form::text('title['.$va->code.']', null, ['class'=>'form-control','required'=>'required',"placeholder"=>"Նկարագրություն "]) }}
                    <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('title.'.$va->code) }} </p>
                </div>
            @endforeach
            <div class="col-md-3">
                <label for="" class="">Հերթականություն</label>
                {{ Form::number('order', $value->order, ['class'=>'form-control','required'=>'required',"placeholder"=>"Հերթականություն"]) }}
                <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('order') }} </p>
            </div>
            <h5 class="modal-title" style="padding: 0 1rem;">Արժեքներ</h5>
            <div class="col-md-12">
               <a href="#" class="btn btn-secondary pluse" style="padding: 0 1rem;">+</a>
            </div>
            <div class="col-md-12 minusPlus">
                <div class="row">
                    <div class="col-md-5">
                        <label for="" >Արժեքներ</label>
                        {{ Form::text('value[]', null, ['class'=>'form-control','required'=>'required',"placeholder"=>"Արժեքներ"]) }}
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('value') }} </p>
                    </div>
                    <div class="col-md-5">
                        <label for="" class="">Հերթականություն</label>
                        {{ Form::number('valueOrder[]', null, ['class'=>'form-control','required'=>'required',"placeholder"=>"Հերթականություն"]) }}
                        <p style="color:red;height: 14px;width: 100%;"> {{ $errors->first('valueOrder') }} </p>
                    </div>
                    <div class="col-md-2" style='display: none;'>
                        <label for="" class="col-md-12">Ջնջել</label>
                        <a href="#" class="btn btn-secondary minus" style="padding: 0 1rem;"> - </a>
                    </div>
                </div>
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

@stop
@section('scripts')
<script src="/assets/back/plugins/treeview/jstree.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

<script>
var noteId = 0;
$(document).on("click", ".addSpecifications",function () {
    $("#cpv_id").val(noteId);
    $("#exampleModal").modal("show");
});

    $(document).ready(function () {
        $(".defined_requirements").on("click", function () {
            $(".appand").html("");
            $(".appand").load("/admin/defined_requirements/get_by_cat_id/"+noteId);
        });
        $(".specifications").on("click", function () {
            $(".appand").html("");
            $(".appand").load("/admin/specifications/get_by_cat_id/"+noteId);
            $(".appandiframe").show();
        });
        $("#tree").on(
            "select_node.jstree", function(evt, data){
                $(".defined_requirements").addClass("disabled");
                $(".specifications").addClass("disabled");
                noteId = 0;
                $(".appandiframe").hide();
                $(".appand").html("");
                noteId = data.node.id;
                $(".defined_requirements").removeClass("disabled");
                if(data.node.children.length <= 0){
                    $(".specifications").removeClass("disabled");
                }
            }
        );
        $.jstree.defaults.plugins = ['ui', 'crrm', 'themes', 'cookies'];

        $('#tree').jstree({
            "plugins" : [  "themes", "html_data", "ui", "cookies" ],
            "cookies":{
                    "auto_save":true,
            },
            'core' : {
                data : function (node, cb) {
                    $.ajax({ url : "/admin/cpv/tree/json/{{$type}}","dataType" : "json" }).done(function (data) {
                        x = [];
                        children = [];

                        $.each( data, function( i, val ) {
                            children = getJson(val.children);
                            x[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
                        })
                        cb(x);
                    });
                }
            }
        });
    });
    function getJson(value) {
      var t = [];
       var  children = [];
        $.each( value, function( i, val ) {
            children = getJson(val.children);
            t[i] = { "id" : val.id, "text" : "#"+val.code+" "+val.name, "children":children };
        })
        return t;
    }

   $(document).on("click",".pluse",function () {
        html  = '<div class="row removeRow">';
        html += '    <div class="col-md-5">';
        html += '        <label for="">Արժեքներ</label>';
        html += '        <input class="form-control" required="required" placeholder="Արժեքներ" name="value[]" type="text">';
        html += '        <p style="color:red;height: 14px;width: 100%;">  </p>';
        html += '    </div>';
        html += '    <div class="col-md-5">';
        html += '        <label for="" class="">Հերթականություն</label>';
        html += '        <input class="form-control" required="required" placeholder="Հերթականություն" name="valueOrder[]" type="number">';
        html += '        <p style="color:red;height: 14px;width: 100%;">  </p>';
        html += '    </div>';
        html += '    <div class="col-md-2" >';
        html += '        <label for="" class="col-md-12">Ջնջել</label>';
        html += '        <a href="#" class="btn btn-secondary minus" style="padding: 0 1rem;"> - </a>';
        html += '    </div>';
        html += '</div>';
        $(".minusPlus").append(html);
   });
   $(document).on("click",".minus",function () {
        $(this).parents(".removeRow").remove();
   })


    $(document).on("click", ".addDefinedRequirements",function () {
        $("#cpv_id_definedRequirements").val(noteId);
        $("#addDefinedRequirements").modal("show");
    });

</script>
@stop
