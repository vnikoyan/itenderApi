@extends('admin.layout.main')
@section('breadcrumb_second') Itender @stop
@section('breadcrumb_second') CRM @stop
@section('breadcrumb_active') Գլխավոր @stop
@section('page_title') Գլխավոր @stop

@section('content')

<div class="row">
    <div class="col-lg-8 d-flex align-items-stretch w-100">
        <div class="card w-100">
            <div class="card-header">
                <h4 class="title-text mb-0">Պատրաստված հայտեր</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <img src="/assets/back/assets/images/widgets/monthly-re.png" alt="" height="80" />
                    <div class="align-self-center">
                        <h2 class="mt-0 mb-2 font-weight-semibold">
                            {{$done_applications_count}} Հայտ
                        </h2>
                    </div>
                </div>
                <hr class="hr-dashed" />
                <div class="d-flex justify-content-between bg-light p-2 mt-3 rounded">
                    <div class="align-self-center"><h6 class="m-0 font-weight-semibold">Այս ամսվա ընթացքում</h6></div>
                    <div class="align-self-center">
                        <h4 class="m-0 font-weight-semibold font-20">{{$done_this_month_applications_count}} Հայտ
                            <span class="badge badge-soft-success font-11 ml-2"><i class="fas fa-arrow-up"></i> {{round(($done_this_month_applications_count / $done_applications_count) * 100)}}%</span>
                        </h4>
                    </div>
                </div>
            </div>
            <!--end card-body-->
        </div>
    </div>
    <div class="col-lg-4 d-flex align-items-stretch">
        <div class="card dash-data-card text-center w-100">
            <div class="card-header">
                Տուժանքի գումար
            </div>
            <div class="card-body">
                <div class="icon-info mb-3">
                    <img src="/assets/back/assets/images/widgets/dollar.png" alt="" height="80" />
                </div>
                <h3 class="text-dark">{{$injury_sum}}դր․</h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
@stop
    @section('scripts')
    <script type="text/javascript">
    </script>
@stop