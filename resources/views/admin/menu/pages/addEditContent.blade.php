<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('assets/back/css/bootsrtap-form-helpers/dist/css/bootstrap-formhelpers.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/plugins/icons/css/ionicons.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/plugins/bootstrap-3.3.6/css/bootstrap.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/plugins/font-awesome-4.5.0/css/font-awesome.min.css')}}" />
        <!-- Start of KEditor styles -->
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/dist/css/keditor-1.1.5.min.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/dist/css/keditor-components-1.1.5.css')}}" />
        <!-- End of KEditor styles -->
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/css/examples.css')}}" />
        <script type="text/javascript" src="{{asset('/assets/back/keditor/plugins/jquery-1.11.3/jquery-1.11.3.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/back/keditor/examples/plugins/bootstrap-3.3.6/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/back/keditor/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/back/keditor/plugins/jquery.nicescroll-3.6.6/jquery.nicescroll.min.js?v=asd')}}"></script>
        <script src="{{asset('assets/back/plugins/ckeditor/ckeditor.js')}}"></script>
        <script src="{{asset('assets/back/keditor/plugins/ckeditor-4.5.6/config.js')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/back/keditor/plugins/ckeditor-4.5.6/adapters/jquery.js')}}"></script>
        <!-- Start of KEditor scripts -->
        <script type="text/javascript" src="{{asset('/assets/back/keditor/dist/js/keditor-1.1.5.js?v=123qweqwe')}}"></script>
        <script type="text/javascript" src="{{asset('/assets/back/keditor/dist/js/keditor-components-1.1.5.js?v=asd')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('/assets/back/keditor/css/st.css?v=asd')}}">
    </head>
    <body>
        <div class="container">
            <div class="col-xs-6">
                <table id="datatable_col_reorder" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th data-hide="phone">ID</th>
                        <th data-class="expand"><i class="fa fa-fw fa-language text-muted hidden-md hidden-sm hidden-xs"></i> Language</th>
                        <th data-hide="phone">Fallback Locale</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($language as $value)
                        <tr>
                            <td>{{ $value->id }}</td>
                            <td><span class="bfh-languages" data-language="{{ $value->code }}_{{ $value->country_code }}" data-flags="true"></span>{{ $value->code }}_{{ $value->country_code }}</td>
                            <td>
                                @if ($value->fallback_locale === 1)
                                    <span class="label label-success">On</span>
                                @else
                                    <span class="label label-danger">Off</span>
                                @endif
                            </td>
                            <td>
                                <ul class="demo-btns">
                                    <li>
                                        <a href="/admin/pages/edit_content/{{$pages->id}}/{{ $value->code }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>
                                            Edit</a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container">
            <div  id="editor">
                    <section>
                        {!!$html!!}
                    </section>
            </div>
        </div>
        <button id="save" >Save</button>
        <style type="text/css" media="screen">
            #editorcontent{
                display: none
            }
        </style>
        {{ Form::model($pages,array('class'=>'smart-form','id'=>'formhtml'))}}
            {{ Form::textarea('html', $pages->html, $attributes = array('id'=>'editorcontent'))}}
        {{Form::close()}}

        <script type="text/javascript">
            $(function() {
                $( "#save" ).on("click",function( event ) {
                  event.preventDefault();
                  $("#editorcontent").val($('#editor').keditor('getContent'));
                   $( "#formhtml" ).submit();
                });
            

                x = $('#editor').keditor({
                    contentAreasSelector: '#header, #body, #footer'
                    ,filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images', 
                                  filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}', 
                                  filebrowserBrowseUrl: '/laravel-filemanager?type=Files', 
                                  filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
                });


  

                
            });
        </script>
        
    </body>
</html>
