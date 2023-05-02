@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') Լեզու @stop
@section('breadcrumb_active') {{ $language->name }} Լեզու @stop
@section('page_title') {{ $language->name }} Լեզու @stop
@section('content')
<link type="text/css" href="/assets/back/plugins/x-editable/css/bootstrap-editable.css" rel="stylesheet">
<input type="hidden" name="id" value="{{ $language->id }}"  data-code="{{ $language->code }}">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mb-2">
                    <div class="card">
                        <div class="card-body">
                            @if(!empty($translation["default"]))
                                <form id="smart-form-register" class="smart-form" action="/admin/language/edit/{{ $language->id }}"  method="post">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                @foreach($translation["default"]  as $name => $lang)
                                                    <a class="nav-link waves-effect waves-light" id="{{$name}}-tab" data-toggle="pill" href="#{{$name}}" role="tab" aria-controls="{{$name}}" aria-selected="true">{{$name}}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="tab-content mo-mt-2" id="v-pills-tabContent">
                                                @foreach($translation["default"] as $name => $lang)
                                                    <div class="tab-pane fade" id="{{$name}}" role="tabpanel" aria-labelledby="{{$name}}-tab">
                                                        <table class="table table-striped mb-0">
                                                            @foreach($lang as $key => $val)
                                                                @if( !is_array($val) )
                                                                    <tr>
                                                                        <td>{{$lang[$key]}}</td>
                                                                        <td><a style="{{ (!isset($translation['current'][$name][$key]))?'font-style: italic; color: #D14;':'' }}" 
                                                                            href="#" 
                                                                            class="translate inlineInput" 
                                                                            id="{{ $name . $key }}"
                                                                            data-name="{{ $name . $key }}"
                                                                            data-lang="{{ $name }}" data-lang-key="{{ $key }}"
                                                                            data-type="text" 
                                                                            data-pk="1"
                                                                            data-original-title="Enter {{ $key }}"
                                                                            data-title="Enter {{$key}}">{{ isset($translation['current'][$name][$key])?$translation['current'][$name][$key]: $val }}</a></td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </table>
                                                        <div class="form-group">
                                                            <button  type="submit" data-lang="{{ $name }}" class="lang-submit btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Պահպանել</button>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts')
<script src="{{asset('/assets/back/plugins/moment/moment.js')}}"></script>
<script src="{{asset('/assets/back/plugins/x-editable/js/bootstrap-editable.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-xeditable.init.js?123ws')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mockjax/2.5.1/jquery.mockjax.min.js"></script>
<script type="text/javascript">
        var lang = {
            messages: {},
            validation: {},
        };

        function pushTranslation(obj) {
            id = '#' + obj.name;
            console.log("2123");
            console.log(obj);
            var $input = $(id);
            $input.removeAttr('style');
            var title = $input.data('lang');
            var key = $input.data('lang-key');
            var value = obj.value;
            if (title == 'custom') {
                if (typeof lang.validation.custom == 'undefined') {
                    lang.validation.custom = {};
                    lang.validation.custom[key] = {};
                }
                lang.validation.custom[key][$input.data('rule')] = value;
            } else {
                lang[title][key] = value;
            }
        }
        $.mockjaxSettings.responseTime = 500;
        $.fn.editable.defaults.url = '/admin/language/edit';
        $.mockjax({
            url: '/admin/language/edit',
            response: function (settings) {
                console.log("123");
                console.log(settings.data);
                pushTranslation(settings.data);
            }
        });

        $('.lang-submit').click(function (e) {
                e.preventDefault();
                var $id = $('input[name=id]');
                var code = $id.data('code');
                var id = $id.val();
                var $button = $(this);
                var chapter = $button.data('lang');
           
                $.ajax({
                    url: '{{ url("/admin/language/edit") }}',
                    type: 'Post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        language: lang[chapter],
                        code: code,
                        id: id,
                        chapter: chapter,
                    },
                    beforeSend: function () {
                        $button.html('<i class="fa fa-spinner fa-spin"></i> Saving...');
                    },
                    success: function (data) {
                        if (data == 0) {
                            Swal.fire({ icon: "error", title: "Oops...", text: "Ինչ որ բան այնպես չգնաց!", footer: "" });
                        } else {
                            Swal.fire({ icon: "success", title: "asd", text: "Հաջողությամբ պահվեց", footer: "" });
                            lang[chapter] = {};
                        }
                        $button.html('<i class="fa fa-save"></i> Submit');
                    }
                });
            });

</script>
@stop