<div class="leftbar-tab-menu">
    <!-- leftbar-tab-menu -->
        <div class="leftbar-tab-menu">
            <div class="main-icon-menu pt-0">
                <nav class="nav">
                    <a href="#menuHome" class="nav-link parentMenu" data-toggle="tooltip-custom" data-placement="right" title="" data-original-title="Գլխավոր" data-trigger="hover">
                        <i data-feather="monitor" class="align-self-center menu-icon icon-dual"></i>
                    </a>
                    @if(Auth::guard('admin')->user()->id == 1)
                            @foreach(Layout::getPermission() as $key => $value)
                                    <a href="#menu_{{$value->first()->name}}" class="nav-link parentMenu" data-toggle="tooltip-custom"  data-placement="right" title="" data-original-title="{{$value->first()->code}}" style="margin-top: 5px;">
                                        {{-- <i data-feather="{{$value->first()->icon}}" class="align-self-center menu-icon icon-dual"></i> --}}
                                        <i class="menu-icon fa fa-{{$value->first()->icon}}"></i>
                                    </a>
                            @endforeach
                    @else
                            @foreach(Auth::guard('admin')->user()->getAllPermissions()->groupBy('name') as $key => $value)
                                    <a href="#menu_{{$value->first()->name}}" class="nav-link parentMenu" data-placement="right" title="" data-original-title="{{$value->first()->code}}" style="margin-top: 5px;">
                                        {{-- <i data-feather="{{$value->first()->icon}}" class="align-self-center menu-icon icon-dual"></i> --}}
                                        <i class="menu-icon fa fa-{{$value->first()->icon}}"></i>
                                    </a>
                            @endforeach
                    @endif
                </nav>
            </div>
        </div>
        <div class="main-menu-inner active" >
            <div class="topbar-left">
                <a href="/admin" class="logo">
                    <span><img src="/assets/back/assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark" /> <img src="/assets/back/assets/images/logo.png" alt="logo-large" class="logo-lg logo-light" /></span>
                </a>
            </div>
            <div class="menu-body subMenu" style="overflow: auto;">

                <div id="menuHome" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Գլխավոր</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link" href='/admin'>
                                Գլխավոր
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_admin" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Ադմին</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.index') }}" href='/admin/admin'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.create') }}" href='/admin/admin/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_user" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Օգտատերեր</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('user.index') }} {{ Menu::isActive('user.edit') }}" href='/admin/user'>
                                Դիտել
                            </a>
                        </li>
                        <!-- <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('user.create') }} {{ Menu::isActive('user.edit') }}" href='/admin/user/create'>
                                Ստեղծել
                            </a>
                        </li> -->
                    </ul>
                </div>
                <div id="menu_user_state" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Պետական օգտատերեր</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('user_state.index') }} {{ Menu::isActive('user_state.edit') }}" href='/admin/user_state'>
                                Դիտել
                            </a>
                        </li>
                        <!-- <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('user_state.create') }} {{ Menu::isActive('user_state.edit') }}" href='/admin/user_state/create'>
                                Ստեղծել
                            </a>
                        </li> -->
                    </ul>
                </div>
                <div id="menu_email" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Էլ․ նամակ</h6>
                    </div>
                    <ul class="nav">
                       <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.message') }}" href='/admin/send/message'>
                                Ողարկել նամակ
                            </a>
                        </li>
                       <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.addParticipants') }}" href='/admin/add/participants'>
                               Email-մասնակիցներին 
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_package" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title"> Փաթեթ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            @if(Auth::guard('admin')->user()->id == 1)
                                <a class="nav-link {{ Menu::isActive('package.index') }} {{ Menu::isActive('package.edit') }}" href='/admin/package'>
                                    Մասնավոր փաթեթներ
                                </a>
                                <a class="nav-link {{ Menu::isActive('package_state.index') }} {{ Menu::isActive('package_state.edit') }} {{ Menu::isActive('package_state.create') }}" href='/admin/package_state'>
                                Պետական փաթեթներ
                                </a>
                            @else
                                <a class="nav-link {{ Menu::isActive('package_state.index') }} {{ Menu::isActive('package_state.edit') }} {{ Menu::isActive('package_state.create') }}" href='/admin/package_state'>
                                Պետական փաթեթներ
                                </a>
                            @endif
{{--                             <a class="nav-link {{ Menu::isActive('package_state.index') }} {{ Menu::isActive('admin.user.addPackageStateView') }} {{ Menu::isActive('admin.user.addPackageStateView') }}" href='/admin/add/package_state'>
                               Ավելացնել պետական փաթեթ
                            </a>
                            <a class="nav-link {{ Menu::isActive('package_state.index') }} {{ Menu::isActive('admin.user.addPackageView') }} {{ Menu::isActive('admin.user.addPackageView') }}" href='/admin/add/package'>
                               Ավելացնել մասնավոր փաթեթ
                            </a> --}}
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">ՎՃԱՐՈՒՄՆԵՐ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            @if(Auth::guard('admin')->user()->id == 1)
                                <a class="nav-link {{ Menu::isActive('admin.order.private') }}" href='/admin/order'>
                                    Մասնավոր
                                </a>
                                <a class="nav-link {{ Menu::isActive('admin.orderState.state') }}" href='/admin/order-state'>
                                Պետական
                                </a>
                             @else
                                <a class="nav-link {{ Menu::isActive('admin.orderState.state') }}" href='/admin/order-state'>
                                    Պետական
                                </a>
                            @endif
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Վճարումների պատմություն</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.order.paymentHistory') }}" href='/admin/payment-history'>
                                Ցուցակ
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_settings" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Լեզու</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('lang.list') }} {{ Menu::isActive('lang.edit') }}" href='/admin/language'>
                                Դիտել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Չափման միավորներ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('units.index') }} {{ Menu::isActive('units.edit') }}" href='/admin/units'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('units.create') }} {{ Menu::isActive('units.edit') }}" href='/admin/units/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Կանոնակարգ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('regulation.index') }} " href='/admin/regulation'>
                                Դիտել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">ՈՒղեցույցներ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('guide.index') }} " href='/admin/guide'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('guide.create') }} {{ Menu::isActive('guide.edit') }}" href='/admin/guide/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Սև ցուցակ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('black_lists.index') }} " href='/admin/black_lists'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('black_lists.create') }} {{ Menu::isActive('black_lists.edit') }}" href='/admin/black_lists/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Տեղեկատվություն</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('info.index') }} " href='/admin/info'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('info.create') }} {{ Menu::isActive('info.edit') }}" href='/admin/info/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">ՀՏՀ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('faq.index') }} " href='/admin/faq'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('faq.create') }} {{ Menu::isActive('faq.edit') }}" href='/admin/faq/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Բողոքարկում </h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('protest.index') }} " href='/admin/protest'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('protest.create') }} {{ Menu::isActive('protest.edit') }}" href='/admin/faq/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Նորություններ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('event.index') }} " href='/admin/event'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('event.create') }} {{ Menu::isActive('event.edit') }}" href='/admin/event/create'>
                                Ստեղծել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('event.subscribers') }} {{ Menu::isActive('event.edit') }}" href='/admin/event/subscribers'>
                                Բաժանորդներ
                            </a>
                        </li>
                    </ul>

                    <div class="title-box">
                        <h6 class="menu-title">ԳՈՐԾԸՆԿԵՐՆԵՐ</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('co_workers.index') }} " href='/admin/co_workers'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('co_workers.create') }} {{ Menu::isActive('co_workers.edit') }}" href='/admin/co_workers/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>

                    <div class="title-box">
                        <h6 class="menu-title">Ֆինանսական դասակարգչի</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('classifier.index') }} " href='/admin/classifier'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('classifier.create') }} {{ Menu::isActive('classifier.edit') }}" href='/admin/classifier/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>

                </div>
                <div id="menu_menu" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Էջեր</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.pages.index') }} {{ Menu::isActive('admin.pages.edit') }}" href='/admin/pages'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.pages.create') }}" href='/admin/pages/create'>
                                Ստեղծել
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Մենյու</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.menu.index') }} {{ Menu::isActive('admin.menu.view') }}" href='/admin/menu'>
                                Դիտել
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_cpv" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Cpv</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('cpv.index') }} {{ Menu::isActive('cpv.edit') }}" href='/admin/cpv'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('cpv.tree') }}" href='/admin/cpv/tree/1'>
                                Ապրանքներ
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('cpv.tree') }}" href='/admin/cpv/tree/2'>
                                Ծառայություններ
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('cpv.tree') }}" href='/admin/cpv/tree/3'>
                                Աշխատանքներ
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('cpv.manualAddCpvView') }}" href='/admin/manual/add/cpv'>
                                ավելացնել Cpv
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_tender" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Չհաստատված</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('tender_state_parser.index') }} {{ Menu::isActive('tender_state_parser.edit') }}" href='/admin/tender_state_parser'>
                                Ըստ բաժինների
                            </a>
                        </li>
                       <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('tender_state_parser.allTenders') }} {{ Menu::isActive('tender_state_parser.allTenders') }}" href='/admin/tender_state_parser/all/tenders'>
                                Բոլորը
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Պետական</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="is_competition competitionli nav-link {{ Menu::isActive('tender_state.index') }} {{ Menu::isActive('tender_state.create') }} {{ Menu::isActive('tender_state.edit') }}" href='/admin/tender_state'>
                                Մրցույթներ
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="is_not_competition competitionli nav-link {{ Menu::isActive('tender_state.index4') }} {{ Menu::isActive('tender_state.create4') }} {{ Menu::isActive('tender_state.edit') }}" href='/admin/tender_state/4'>
                                Հայտարարություններ
                            </a>
                        </li>
                    </ul>
                    <div class="title-box">
                        <h6 class="menu-title">Մասնավոր</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="is_competition competitionli nav-link {{ Menu::isActive('tender_state.tendersByManager') }}" href='/admin/manager/tenders'>
                                Մրցույթներ
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_itender" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Itender</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('itender.index') }} {{ Menu::isActive('itender.edit') }}" href='/admin/itender'>
                                Դիտել
                            </a>
                        </li>
                        <li class="nav-item subMenuItem">
                            <a class="nav-link  {{ Menu::isActive('itender.time') }}" href='/admin/itender/time'>
                                Ժամկետներ
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="menu_organizer" class="main-icon-menu-pane">
                    <div class="title-box">
                        <h6 class="menu-title">Պատվիրատու</h6>
                    </div>
                    <ul class="nav">
                        <li class="nav-item subMenuItem">
                            <a class="nav-link {{ Menu::isActive('admin.organizer') }} {{ Menu::isActive('admin.organizer') }}" href='/admin/organizer'>
                                ավելացնել պատվիրատու
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="menu_bank_secure_stats" class="main-icon-menu-pane">
                <div class="title-box">
                    <h6 class="menu-title">Երաշխիքի հաշվետվություն</h6>
                </div>
                <ul class="nav">
                    <li class="nav-item subMenuItem">
                        <a class="nav-link {{ Menu::isActive('admin.bank_secure_stats') }} {{ Menu::isActive('admin.bank_secure_stats') }}" href='/admin/bank_secure_stats'>
                            ավելացնել պատվիրատու
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    <!-- end leftbar-tab-menu-->
</div>
<style>
    .nav-link .menu-icon{
        font-size: 18px !important;
        color: white !important;
    }

</style>