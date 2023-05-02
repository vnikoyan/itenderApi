<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card dr-pro-pic">
            <div class="card-body">
                <div class="">
                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Անվանում</th>
                                <th>Հերթականություն</th>
                                <th>Նկարագրություն</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($defined_requirementsList as $key => $value)
                            <tr>
                                <td>{{$value->title}}</td>
                                <td>{{$value->order}}</td>
                                <td>
                                    {!! $value->description !!}
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