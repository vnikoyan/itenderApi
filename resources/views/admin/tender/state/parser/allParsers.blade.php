@extends('admin.layout.main')
@section('breadcrumb_first') Չհաստատված Հայտարարություններ @stop
@section('breadcrumb_active') Ցանկ @stop
@section('page_title') Չհաստատված Մրցույթների Հայտարարությունների Ցանկ @stop
@section('content')
    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card dr-pro-pic">
                <div class="card-body">
                	<table class="table section-table">
					  <tr>
					    <th>Բաժին</th>
					    <th>Դիտել</th>
					  </tr>
					  <tr style="background-color:aliceblue">
					    <td><a href= "https://gnumner.minfin.am/hy/page/hayteri_bacman_nisti_ardzanagrutyunner/" class= "parser-link">Հայտերի բացման նիստի արձանագրություններ</a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','HBNA') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr style="background-color:aliceblue">
					    <td><a href= "https://gnumner.minfin.am/hy/page/hayteri_gnahatman_nisti_ardzanagrutyunner/" class= "parser-link">Հայտերի գնահատման նիստի արձանագրություններ</a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','HGNA') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/elektronayin_achurdi_haytararutyun_ev_hraver/" class= "parser-link">Էլեկտրոնային աճուրդի հայտարարություն և հրավեր </a> </td>
					    <td><a href = "{{ route('tender_state_parser.getTenderByType','ELAH')}}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/erkpul_mrcuyti_nakhaorakavorman_haytararutyun/" class= "parser-link">Երկփուլ մրցույթի նախաորակավորման հայտարարություն </a> </td>
					    <td><a  href ="{{ route('tender_state_parser.getTenderByType','EMNH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/bac_mrcuyti_nakhaorakavorman_haytararutyun/" class= "parser-link">Բաց մրցույթի նախաորակավորման հայտարարություն </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','BMNH') }}"class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/gnanshman_harcman_nakhaorakavorman_haytararutyun/" class= "parser-link">Գնանշման հարցման նախաորակավորման հայտարարություն </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','GHNH') }}"class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/_pak_npatakayin_mrcuyti_nakhaorakavorman_haytararutyun/" class= "parser-link">Փակ նպատակային մրցույթի նախաորակավորման հայտարարություն </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','PNMNH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/pak_parberakan_mrcuyti_nakhaorakavorman_haytararutyun_ev_hraver/" class= "parser-link">Փակ պարբերական մրցույթի նախաորակավորման հայտարարություն և հրավեր </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','PPMNH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/_mek_andzic_gnumneri_katarman_haytararutyun_ev_hraver/" class= "parser-link">Մեկ անձից գնումների կատարման հայտարարություն և հրավեր </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','MAGKH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/tsragri_irakanacman_grasenyakneri_koghmic_katarvogh_gnumneri_masin_haytararutyunner_ev_hraverner/" class= "parser-link">Ծրագրի իրականացման գրասենյակների կողմից կատարվող գնումների մասին հայտարարություններ և հրավերներ </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','TIGKKGMH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/knqvats_paymanagri_masin_haytararutyun/" class= "parser-link">Կնքված պայմանագրի մասին հայտարարություն </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','KMH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/hraverum_katarvats_popokhutyunner/" class= "parser-link">Հրավերում կատարված փոփոխություններ </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','HKP') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/otarerkrya_petutyunneri_koghmic_kazmakerpvogh_gnumner/" class= "parser-link">Օտարերկրյա պետությունների կողմից կազմակերպվող գնումներ </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','OPKKG') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/mayr_ator_s_ejmiatsni_haytararutyunner/" class= "parser-link">Մայր Աթոռ Ս. Էջմիածնի հայտարարություններ </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','MAEH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href="https://gnumner.minfin.am/hy/page/hanrayin_kazmakerputyunneri_koghmic_katarvogh_gnumneri_veraberyal_haytararutyunner/" class= "parser-link">Հանրային կազմակերպությունների կողմից կատարվող գնումների վերաբերյալ հայտարարություններ </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','HKKKGVH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr> 
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/bac_mrcuyti_haytararutyun_ev_hraver/" class= "parser-link">Բաց մրցույթի հայտարարություն և հրավեր </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','BMHH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://gnumner.minfin.am/hy/page/gnanshman_harcman_haytararutyun_ev_hraver/" class= "parser-link">Գնանշման հարցման հայտարարություն և հրավեր </a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','GHHH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://www.gnumner.am/hy/page/paymanagir_knqelu_masin_haytararutyun/" class= "parser-link">Պայմանագիր կնքելու մասին հայտարարություն</a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','PKMH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://www.gnumner.am/hy/page/chkayacats_gnman_yntacakargi_masin_haytararutyunner/" class= "parser-link">Չկայացած գնման ընթացակարգի մասին հայտարարություն</a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','CHGYMH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					  <tr>
					    <td><a href= "https://www.gnumner.am/hy/page/hraveri_veraberyal_tramadrvats_parzabanumneri_masin_haytararutyun_1/" class= "parser-link">Հրավերի վերաբերյալ տրամադրված պարզաբանումների մասին հայտարարություն</a> </td>
					    <td><a href ="{{ route('tender_state_parser.getTenderByType','HVTPMH') }}" class="view-btn"><i class="fa fa-edit"></i></a></td>
					  </tr>
					</table>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@stop
@section('scripts')
<link href="{{asset('/assets/back/plugins/dropify/css/dropify.min.css')}}" rel="stylesheet">
<style>
	.section-table{
		width: 100%!important;
		text-align: center;
	}
	.section-table tr td{
		padding: 10px;
		color: #1E7BB7;
		font-weight: 800;
		border: 1px solid #f4f4f4;
	}
	.section-table tr th{
		border: 1px solid #f4f4f4;
	}
	.parser-link{
		color: #1E7BB7;
	}
	.view-btn{
		font-size: 20px;
		cursor: pointer;
	}
</style>
<script src="{{asset('/assets/back/plugins/dropify/js/dropify.min.js')}}"></script>
<script src="{{asset('/assets/back/assets/pages/jquery.form-upload.init.js')}}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>

<script src="{{asset('/assets/back/assets/pages/jquery.form-editor.init.js?12ewqdsdcv') }}"></script>

@stop