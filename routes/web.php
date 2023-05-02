<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;


Route::get('/', 'HomeController@home')->name('home');
Auth::routes();


Route::group(['middleware' => ['auth.user']], function () {
    // login protected routes.
	Route::get('/home', 'HomeController@index')->name('home');

});
Route::get('/fixDb',[
	'uses' => "PdfController@fixDb",
	'as'   => 'pdf.fixDb'
]);
Route::get('/document/generation',[
	'uses' => "PdfController@showDoccument",
	'as'   => 'pdf.show'
]);
Route::post('/download/document',[
	'uses' => "PdfController@downloadDoccument",
	'as'   => 'pdf.download'
]);

Route::get('procurement/export/{id}', 'Api\Procurement\ProcurementController@downloadFile');
Route::get('rows-example/export', 'Api\Organize\OrganizeRowController@downloadFile');

Route::get('payment', [
	'uses' => "Api\Settings\UserPaymentController@getPayment",
	'as'   => 'user.getPayment'
]);

// admin panel
Route::group([
    'namespace' => 'Admin',
    'prefix'    => 'admin'
], function () {
	// Authentication Routes...
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
	Route::post('login', 'Auth\LoginController@login');
	Route::get('logout', 'Auth\LoginController@logout')->name('admin.logout');


	Route::group(['middleware' => ['auth.admin']], function () {

		Route::get('/', 'HomeController@index')->name('admin.home');

		Route::get('admin/getPermission/{id}', 'Admin\AdminController@getPermission')->name('admin.admin.getPermission');
		Route::put('admin/getPermission/{id}', 'Admin\AdminController@updateAdminPermission')->name('admin.admin.updatePermission');
		Route::get('admin/view', 'Admin\AdminController@index');
		Route::get('admin/{id}/delete', 'Admin\AdminController@destroy');
		Route::resource('admin','Admin\AdminController');

		/*UserController*/
		Route::get('user/getCat', [
			'uses' => "User\UserController@getCat",
			'as'   => 'admin.user.tableData'
		]);
		/*UserController*/
		Route::get('user/tableData/{type?}', [
			'uses' => "User\UserController@tableData",
			'as'   => 'admin.user.tableData'
		]);
		/*UserController*/
		Route::get('user/delete/{id}', [
			'uses' => "User\UserController@destroy",
			'as'   => 'admin.user.delete'
		]);		

		Route::get('user/edit/{id}/{orderId}', [
			'uses' => "User\UserController@getUserWithOrder",
			'as'   => 'admin.user.getUserWithOrder'
		]);

		Route::get('user/delete/with/order/{id}/{orderId}', [
			'uses' => "User\UserController@deleteUserWithOrder",
			'as'   => 'admin.user.deleteUserWithOrder'
		]);

		Route::resource('user','User\UserController');

		Route::get('package/tableData', [
			'uses' => "User\PackageController@tableData",
			'as'   => 'admin.user.tableData'
		]);
		Route::resource('package','User\PackageController');

		Route::get('package_state/tableData', [
			'uses' => "User\PackageStateController@tableData",
			'as'   => 'admin.user.tableData'
		]);

		Route::get('add/package_state', [
			'uses' => "User\PackageStateController@addPackageStateView",
			'as'   => 'admin.user.addPackageStateView'
		]);

		Route::get('add/package', [
			'uses' => "User\PackageStateController@addPackageView",
			'as'   => 'admin.user.addPackageView'
		]);		

		Route::post('/order/search/user', [
			'uses' => "User\PackageController@searchUser",
			'as'   => 'admin.user.searchUser'
		]);				

		Route::post('/add/order', [
			'uses' => "Order\OrderController@addOrder",
			'as'   => 'admin.user.addOrder'
		]);				

		Route::post('add/package_state', [
			'uses' => "User\PackageStateController@addPackageState",
			'as'   => 'admin.user.addPackageState'
		]);

		Route::post('search/by/name', [
			'uses' => "User\PackageStateController@searchByName",
			'as'   => 'admin.user.searchByName'
		]);

		Route::get('package_state/delete/{id}', [
			'uses' => "User\PackageStateController@destroy",
			'as'   => 'admin.user.delete'
		]);
		Route::resource('package_state','User\PackageStateController');


		Route::get('order/tableData', [
			'uses' => "Order\OrderController@tableDataPrivate",
			'as'   => 'admin.user.tableData'
		]);

		Route::get('order/tableDataState', [
			'uses' => "Order\OrderController@tableDataState",
			'as'   => 'admin.user.tableData'
		]);

		Route::get('order/tableDataPaymentHistory', [
			'uses' => "Order\OrderController@tableDataPaymentHistory",
			'as'   => 'admin.user.tableData'
		]);

		Route::get('order', [
			'uses' => "Order\OrderController@Private",
			'as'   => 'admin.order.private'
		]);		

		Route::get('delete/order/{id}', [
			'uses' => "Order\OrderController@deleteOrder",
			'as'   => 'admin.order.deleteOrder'
		]);

		Route::get('delete/order/state/{id}', [
			'uses' => "Order\OrderController@deleteOrderState",
			'as'   => 'admin.order.deleteOrderState'
		]);

		Route::get('pause/order/{id}', [
			'uses' => "Order\OrderController@pauseOrder",
			'as'   => 'admin.order.pauseOrder'
		]);

		Route::get('continue/order/{id}', [
			'uses' => "Order\OrderController@continueOrder",
			'as'   => 'admin.order.continueOrder'
		]);		

		Route::get('delete/order/state/{id}', [
			'uses' => "Order\OrderController@deleteOrderState",
			'as'   => 'admin.order.deleteOrderState'
		]);

		Route::get('approve/order/{id}', [
			'uses' => "Order\OrderController@approveOrder",
			'as'   => 'admin.order.approveOrder'
		]);		

		Route::get('approve/order/state/{id}', [
			'uses' => "Order\OrderController@approveOrderState",
			'as'   => 'admin.order.approveOrderState'
		]);

		Route::get('pause/order/state/{id}', [
			'uses' => "Order\OrderController@pauseOrderState",
			'as'   => 'admin.order.pauseOrderState'
		]);

		Route::get('continue/order/state/{id}', [
			'uses' => "Order\OrderController@continueOrderState",
			'as'   => 'admin.order.continueOrderState'
		]);


		Route::get('payment-history', [
			'uses' => "Order\OrderController@paymentHistory",
			'as'   => 'admin.order.paymentHistory'
		]);

		Route::get('order-state', [
			'uses' => "Order\OrderController@State",
			'as'   => 'admin.orderState.state'
		]);






		/*UserStateController*/
		Route::get('user_state/tableData/{type?}', [
			'uses' => "User\UserStateController@tableData",
			'as'   => 'admin.user.tableData'
		]);
		/*UserStateController*/
		Route::get('user_state/contrat/{user_id}', [
			'uses' => "User\UserStateController@contrat",
			'as'   => 'admin.user.tableData'
		]);

		// Route::get('user_state/delete/{id}', [
		// 	'uses' => "User\UserStateController@destroy",
		// 	'as'   => 'admin.user.delete'
		// ]);

		Route::get('user_state/delete/{id}', [
			'uses' => "User\UserStateController@removeUser",
			'as'   => 'admin.removeUser'
		]);

        Route::put('user_state/org/{id}', [
            'uses' => "User\UserStateController@editOrg",
            'as'   => 'admin.user.org'
        ]);

        Route::resource('user_state','User\UserStateController');
		/*EndUserStateController*/



		Route::post('user_state/addDivisions/{org_id}', [
			'uses' => "User\UserStateController@addDivisions",
			'as'   => 'admin.user.delete'
		]);

		
		Route::get('send/message', [
			'uses' => "Admin\AdminController@sendMessage",
			'as'   => 'admin.message'
		]);


		Route::post('send/message', [
			'uses' => "Admin\AdminController@adminSendMessage",
			'as'   => 'admin.sendMessage'
		]);

		Route::get('add/participants', [
			'uses' => "Admin\AdminController@addParticipants",
			'as'   => 'admin.addParticipants'
		]);


		Route::post('admin/new/participants', [
			'uses' => "Admin\AdminController@addNewParticipants",
			'as'   => 'admin.addNewParticipants'
		]);

		Route::get('bank_secure_stats', [
			'uses' => "Admin\AdminController@bankSecureStats",
			'as'   => 'admin.bankSecureStats'
		]);		
		
		Route::post('filter/getBankStats',[
			'uses' => "Admin\AdminController@filterBankSecureStats",
			'as'   => 'admin.filterBankSecureStats'
		]);
		
		Route::get('organizer', [
			'uses' => "Admin\AdminController@organizer",
			'as'   => 'admin.organizer'
		]);		


		Route::get('get/organizators/', [
			'uses' => "Admin\AdminController@getOrganizers",
			'as'   => 'admin.getOrganizers'
		]);		

		Route::get('delete/organizator/{id}', [
			'uses' => "Admin\AdminController@deleteOrganizator",
			'as'   => 'admin.deleteOrganizator'
		]);

		Route::post('add/organizer', [
			'uses' => "Admin\AdminController@addOorganizer",
			'as'   => 'admin.addOrganizer'
		]);

		Route::post('get/participants', [
			'uses' => "Admin\AdminController@getParticipants",
			'as'   => 'admin.getParticipants'
		]);

		Route::get('delete/participant/email/{id}', [
			'uses' => "Admin\AdminController@deleteParticipantEmail",
			'as'   => 'admin.deleteParticipantEmail'
		]);

		Route::get('user_state_children/update', [
			'uses' => "User\UserStateController@update",
			'as'   => 'admin.user.delete'
		]);


		 /*LangController*/
		 Route::get('language', [
			'uses' => "Settings\LanguageController@index",
			'as'   => 'lang.list'
		]);
		Route::any('language/edit/{id?}', [
			'uses' => "Settings\LanguageController@edit",
			'as'   => 'lang.edit'
		]);
		Route::get('language/delete/{id}', ['uses' => "Settings\LanguageController@delete"]);



		/*UnitsController*/
		Route::get('units/tableData', [
			'uses' => "Settings\UnitsController@tableData",
			'as'   => 'units.tableData'
		]);
		/*UnitsController*/
		Route::get('units/contrat/{user_id}', [
			'uses' => "Settings\UnitsController@contrat",
			'as'   => 'units.tableData'
		]);
		Route::get('units/delete/{id}', [
			'uses' => "Settings\UnitsController@destroy",
			'as'   => 'units.delete'
		]);
		Route::resource('units','Settings\UnitsController');
		/*UnitsController*/


			/*UnitsController*/
			Route::get('co_workers/tableData', [
				'uses' => "Settings\CoWorkersController@tableData",
				'as'   => 'co_workers.tableData'
			]);

			Route::get('co_workers/editStatus/{id}', [
				'uses' => "Settings\CoWorkersController@editStatus",
				'as'   => 'co_workers.tableData'
			]);
			/*CoWorkersController*/
			Route::get('co_workers/contrat/{user_id}', [
				'uses' => "Settings\CoWorkersController@contrat",
				'as'   => 'co_workers.tableData'
			]);
			Route::get('co_workers/delete/{id}', [
				'uses' => "Settings\CoWorkersController@destroy",
				'as'   => 'co_workers.delete'
			]);
			Route::resource('co_workers','Settings\CoWorkersController');



		/*GuideController*/

		Route::get('guide/tableData', [
			'uses' => "Settings\GuideController@tableData",
			'as'   => 'guide.tableData'
		]);

		Route::get('guide/contrat/{user_id}', [
			'uses' => "Settings\GuideController@contrat",
			'as'   => 'guide.tableData'
		]);
		Route::get('guide/delete/{id}', [
			'uses' => "Settings\GuideController@destroy",
			'as'   => 'guide.delete'
		]);
		Route::get('guide/file/{id}/{lg}', [
			'uses' => "Settings\GuideController@fileDownload",
			'as'   => 'guide.delete'
		]);
		Route::resource('guide','Settings\GuideController');
		Route::resource('regulation','Settings\RegulationController');

		Route::get('info/tableData', [
			'uses' => "Settings\InfoController@tableData",
			'as'   => 'info.tableData'
		]);

		Route::get('info/contrat/{user_id}', [
			'uses' => "Settings\InfoController@contrat",
			'as'   => 'info.tableData'
		]);
		Route::get('info/delete/{id}', [
			'uses' => "Settings\InfoController@destroy",
			'as'   => 'info.delete'
		]);
		Route::get('info/file/{id}', [
			'uses' => "Settings\InfoController@fileDownload",
			'as'   => 'info.delete'
		]);
		Route::resource('info','Settings\InfoController');

		Route::get('faq/tableData', [
			'uses' => "Settings\FaqController@tableData",
			'as'   => 'info.tableData'
		]);

		Route::get('faq/contrat/{user_id}', [
			'uses' => "Settings\FaqController@contrat",
			'as'   => 'info.tableData'
		]);
		Route::get('faq/delete/{id}', [
			'uses' => "Settings\FaqController@destroy",
			'as'   => 'info.delete'
		]);
		Route::get('faq/file/{id}', [
			'uses' => "Settings\FaqController@fileDownload",
			'as'   => 'info.delete'
		]);
		Route::resource('faq','Settings\FaqController');

		Route::get('protest/tableData', [
			'uses' => "Settings\ProtestController@tableData",
			'as'   => 'info.tableData'
		]);

		Route::get('protest/contrat/{user_id}', [
			'uses' => "Settings\ProtestController@contrat",
			'as'   => 'info.tableData'
		]);
		Route::get('protest/delete/{id}', [
			'uses' => "Settings\ProtestController@destroy",
			'as'   => 'info.delete'
		]);
		Route::get('protest/file/{id}', [
			'uses' => "Settings\ProtestController@fileDownload",
			'as'   => 'info.delete'
		]);
		Route::resource('protest','Settings\ProtestController');

		Route::get('event/image/delete/{id}', [
			'uses' => "Settings\EventController@imageDelete",
			'as'   => 'event.tableData'
		]);
		Route::get('event/tableData', [
			'uses' => "Settings\EventController@tableData",
			'as'   => 'event.tableData'
		]);
		Route::get('event/subscribers/tableData', [
			'uses' => "Settings\EventController@subscribersTableData",
			'as'   => 'event.tableData'
		]);

		Route::get('event/contrat/{user_id}', [
			'uses' => "Settings\EventController@contrat",
			'as'   => 'event.tableData'
		]);
		Route::get('event/delete/{id}', [
			'uses' => "Settings\EventController@destroy",
			'as'   => 'event.delete'
		]);
		Route::get('event/file/{id}', [
			'uses' => "Settings\EventController@fileDownload",
			'as'   => 'event.delete'
		]);
		Route::get('event/subscribers', [
			'uses' => "Settings\EventController@subscribers",
			'as'   => 'event.delete'
		]);
		Route::resource('event','Settings\EventController');



		/*black_lists*/

		Route::get('black_lists/tableData', [
			'uses' => "Settings\BlackListController@tableData",
			'as'   => 'black_lists.tableData'
		]);
		Route::get('black_lists/contrat/{user_id}', [
			'uses' => "Settings\BlackListController@contrat",
			'as'   => 'black_lists.tableData'
		]);
		Route::get('black_lists/delete/{id}', [
			'uses' => "Settings\BlackListController@destroy",
			'as'   => 'black_lists.delete'
		]);
		Route::post('black_lists/fileUploade', [
			'uses' => "Settings\BlackListController@fileUploade",
			'as'   => 'black_lists.fileUploade'
		]);
		Route::resource('black_lists','Settings\BlackListController');



		/*ClassifierController*/

		Route::get('classifier/tableData', [
			'uses' => "Settings\ClassifierController@tableData",
			'as'   => 'classifier.tableData'
		]);
		Route::get('classifier/contrat/{user_id}', [
			'uses' => "Settings\ClassifierController@contrat",
			'as'   => 'classifier.tableData'
		]);
		Route::get('classifier/delete/{id}', [
			'uses' => "Settings\ClassifierController@destroy",
			'as'   => 'classifier.delete'
		]);
		Route::post('classifier/fileUploade', [
			'uses' => "Settings\ClassifierController@fileUploade",
			'as'   => 'classifier.fileUploade'
		]);
		Route::resource('classifier','Settings\ClassifierController');

		/*menuController*/
		Route::get('/menu', [
			'uses' => "Menu\MenuController@index",
			'as'   => 'admin.menu.index'
			]);
			/*MenuController  Edit*/
			Route::any('menu/{id}/edit', [
			'uses' => "Menu\MenuController@addEdit",
			'as'   => 'admin.menu.edit'
			]);
			/*MenuController create */
			Route::any('menu/create', [
			'uses' => "Menu\MenuController@addEdit",
			'as'   => 'admin.menu.create'
			]);
			Route::any('menu/tableData', [
			'uses' => "Menu\MenuController@tableData",
			'as'   => 'admin.menu.tableData'
			]);

			Route::get('menu/{id}/delete', ['uses' => "Menu\MenuController@delete"]);

			Route::get('menu/view/{id}', [
				'uses' => "Menu\MenuPagesController@view",
				'as'   => 'admin.menu.view'
			]);
			Route::get('MenuPages/isPageCheckedSave', [
				'uses' => "Menu\MenuPagesController@isPageCheckedSave",
				'as'   => 'admin.menu.isPageCheckedSave'
			]);
			Route::get('MenuPages/sortTable', [
				'uses' => "Menu\MenuPagesController@sortTable",
				'as'   => 'admin.menu.sortTable'
			]);
		// menu EnD
				// pages
				/*PagesController*/
				Route::get('/pages', [
				'uses' => "Menu\PagesController@index",
				'as'   => 'admin.pages.index'
				]);
				/*PagesController  Edit*/
				Route::any('pages/edit/{id}', [
				'uses' => "Menu\PagesController@addEdit",
				'as'   => 'admin.pages.edit'
				]);
				/*PagesController  Edit*/
				Route::any('pages/edit_content/{id}/{lng?}', [
				'uses' => "Menu\PagesController@addEditContent",
				'as'   => 'admin.pages.edit'
				]);
				/*PagesController create */
				Route::any('pages/create', [
				'uses' => "Menu\PagesController@addEdit",
				'as'   => 'admin.pages.create'
				]);
				Route::any('pages/tableData', [
				'uses' => "Menu\PagesController@tableData",
				'as'   => 'admin.pages.create'
				]);
				Route::get('pages/delete/{id}', ['uses' => "Menu\PagesController@delete"]);
				Route::any('pages/sortTable', ['uses' => "Menu\PagesController@sortTable"]);
			// pages EnD

			/*GuideController*/
			Route::get('cpv/tree/json/{type}', [
				'uses' => "Cpv\CpvController@treeJson",
				'as'   => 'cpv.tree'
			]);

            Route::post('cpv/uploadPotentialClear', 'Cpv\CpvController@uploadPotentialClear');
            Route::post('cpv/uploadPotential', 'Cpv\CpvController@uploadPotential');

			Route::get('cpv/get_cpv_type/{id}', [
				'uses' => "Cpv\CpvController@getByCpvType",
				'as'   => 'cpv.getByCpvType'
			]);
			
			Route::post('search/cpv/parent', [
				'uses' => "Cpv\CpvController@searchCpvParent",
				'as'   => 'cpv.searchCpvParent'
			]);

			Route::get('manual/add/cpv', [
				'uses' => "Cpv\CpvController@manualAddCpvView",
				'as'   => 'cpv.manualAddCpvView'
			]);

			Route::post('manual/add/cpv', [
				'uses' => "Cpv\CpvController@manualAddCpv",
				'as'   => 'cpv.manualAddCpv'
			]);



			Route::get('categories/tree/json/{type}', [
				'uses' => "Categories\CategoriesController@treeJson",
				'as'   => 'cpv.tree'
			]);

			/*GuideController*/
			Route::get('cpv/tree/{type?}', [
				'uses' => "Cpv\CpvController@tree",
				'as'   => 'cpv.tree'
			]);
			/*GuideController*/
			Route::get('cpv/tableData', [
				'uses' => "Cpv\CpvController@tableData",
				'as'   => 'cpv.tableData'
			]);
			Route::get('cpv/contrat/{user_id}', [
				'uses' => "Cpv\CpvController@contrat",
				'as'   => 'cpv.tableData'
			]);
			Route::get('cpv/delete/{id}', [
				'uses' => "Cpv\CpvController@destroy",
				'as'   => 'cpv.delete'
			]);
			Route::post('cpv/fileUploade', [
				'uses' => "Cpv\CpvController@fileUpload",
				'as'   => 'cpv.fileUploade'
			]);

			Route::post('cpv/fileUploadeTranslates', [
				'uses' => "Cpv\CpvController@fileUploadeTranslates",
				'as'   => 'cpv.fileUploadeTranslates'
			]);

			Route::resource('cpv','Cpv\CpvController');
			
			Route::post('get/cpv/by/tender_state/id', [
				'uses' => "Cpv\CpvController@getCpvByTenderStateId",
				'as'   => 'cpv.getCpvByTenderStateId'
			]);

			Route::post('get/cpv/by/text', [
				'uses' => "Cpv\CpvController@getCpvByText",
				'as'   => 'cpv.getCpvByText'
			]);

			Route::post('add/estimated/price/', [
				'uses' => "Tender\State\TenderStateController@uploadEstimatedPrice",
				'as'   => 'tender_state.uploadEstimatedPrice'
			]);

			Route::post('search/user/by/tin', [
				'uses' => "User\UserController@searchUserByTin",
				'as'   => 'user.searchUserByTin'
			]);

			Route::post('get/tender_state/by/password', [
				'uses' => "Tender\State\TenderStateController@getTenderStateByPassword",
				'as'   => 'tender_state.getTenderStateByPassword'
			]);

			/*black_lists*/


			Route::post('itender/rejected/{id}', [
				'uses' => "Itender\ItenderController@rejected",
				'as'   => 'itender.time'
			]);


			Route::get('itender/time', [
				'uses' => "Itender\ItenderController@time",
				'as'   => 'itender.time'
			]);

			Route::post('itender/time', [
				'uses' => "Itender\ItenderController@timeUpdate",
				'as'   => 'itender.timeUpdate'
			]);
			Route::get('itender/tableData/{type?}', [
				'uses' => "Itender\ItenderController@tableData",
				'as'   => 'black_lists.tableData'
			]);
			Route::get('itender/contrat/{user_id}', [
				'uses' => "Itender\ItenderController@contrat",
				'as'   => 'itender.tableData'
			]);

			Route::get('itender/getByid', [
				'uses' => "Itender\ItenderController@getByid",
				'as'   => 'itender.getByid'
			]);
			Route::get('itender/{type?}', [
				'uses' => "Itender\ItenderController@index",
				'as'   => 'itender.index'
			]);

			Route::get('itender/{id}/check', [
				'uses' => "Itender\ItenderController@check",
				'as'   => 'itender.delete'
			]);



			Route::get('itender/delete/{id}', [
				'uses' => "Itender\ItenderController@destroy",
				'as'   => 'itender.delete'
			]);
			Route::post('itender/fileUploade', [
				'uses' => "Itender\ItenderController@fileUploade",
				'as'   => 'itender.fileUploade'
			]);
			Route::resource('itender','Itender\ItenderController');


			Route::get('defined_requirements/get_by_cat_id/{id}', [
				'uses' => "Itender\DefinedRequirementsController@getByCatId",
				'as'   => 'black_lists.tableData'
			]);

			Route::get('defined_requirements/get_by_ajax', [
				'uses' => "Itender\DefinedRequirementsController@getByAjax",
				'as'   => 'black_lists.tableData'
			]);
			Route::get('defined_requirements/tableData', [
				'uses' => "Itender\DefinedRequirementsController@tableData",
				'as'   => 'black_lists.tableData'
			]);
			Route::get('defined_requirements/delete/{id}', [
				'uses' => "Itender\DefinedRequirementsController@destroy",
				'as'   => 'itender.delete'
			]);
			Route::resource('defined_requirements','Itender\DefinedRequirementsController');


			Route::get('specifications/get_by_cat_id/{id}', [
				'uses' => "Cpv\SpecificationsController@getByCatId",
				'as'   => 'black_lists.tableData'
			]);
			Route::get('specifications/get_by_cpv_id/{id}', [
				'uses' => "Cpv\SpecificationsController@getByCpvId",
				'as'   => 'black_lists.tableData'
			]);
			Route::get('specifications/delete/{id}', [
				'uses' => "Cpv\SpecificationsController@destroy",
				'as'   => 'specifications.delete'
			]);
			Route::resource('specifications','Cpv\SpecificationsController');




			/*UnitsController*/
			Route::get('tender_state_parser/tableData/{type?}', [
				'uses' => "Tender\State\ParserStateController@tableData",
				'as'   => 'tender_state_parser.tableData'
			]);

			Route::post('tender_state_parser/get/not/approved', [
				'uses' => "Tender\State\ParserStateController@getNotApproved",
				'as'   => 'tender_state_parser.getNotApproved'
			]);

			Route::get('tender_state_parser/edit/{id}',[
				'uses' => "Tender\State\ParserStateController@tenderStateParserEdit",
				'as'   => 'tender_state_parser.tenderStateParserEdit'
			]);

			Route::get('tender_state_parser/parser/{type}',[
				'uses' => "Tender\State\ParserStateController@getTenderByType",
				'as'   => 'tender_state_parser.getTenderByType'
			]);


			Route::get('tender_state_parser/editStatus/{id}', [
				'uses' => "Tender\State\ParserStateController@editStatus",
				'as'   => 'tender_state_parser.tableData'
			]);
			/*ParserStateController*/
			Route::get('tender_state_parser/contrat/{user_id}', [
				'uses' => "Tender\State\ParserStateController@contrat",
				'as'   => 'tender_state_parser.tableData'
			]);
			Route::get('tender_state_parser/delete/{id}', [
				'uses' => "Tender\State\ParserStateController@destroy",
				'as'   => 'tender_state_parser.delete'
			]);

			Route::get('tender_state_parser/type/{id}', [
				'uses' => "Tender\State\ParserStateController@indexType",
				'as'   => 'tender_state_parser.index'
			]);

			Route::get('tender_state_parser/all/tenders', [
				'uses' => "Tender\State\ParserStateController@allTenders",
				'as'   => 'tender_state_parser.allTenders'
			]);

			Route::resource('tender_state_parser','Tender\State\ParserStateController');


			/*UnitsController*/
			Route::get('tender_state/tableData/{type}', [
				'uses' => "Tender\State\TenderStateController@tableData",
				'as'   => 'tender_state.tableData'
			]);

			Route::get('tender_state/editStatus/{id}', [
				'uses' => "Tender\State\TenderStateController@editStatus",
				'as'   => 'tender_state.tableData'
			]);
			/*TenderStateController*/
			Route::get('tender_state/contrat/{user_id}', [
				'uses' => "Tender\State\TenderStateController@contrat",
				'as'   => 'tender_state.tableData'
			]);
			
			Route::get('tender_state/delete/{id}', [
				'uses' => "Tender\State\TenderStateController@destroy",
				'as'   => 'tender_state.delete'
			]);

			Route::get('manager/tenders',[
				'uses' => "Tender\State\TenderStateController@showManagersTenders",
				'as'   => 'tender_state.tendersByManager'
			]);

			Route::get('manager/tender_state/edit/{id}',[
				'uses' => "Tender\State\TenderStateController@adminEditManagerTender",
				'as'   => 'tender_state.adminEditManagerTender'
			]);

			Route::get('get/manager/tenders',[
				'uses' => "Tender\State\TenderStateController@getManagersTenders",
				'as'   => 'tender_state.getTendersByManager'
			]);
			Route::post('update/manager/tenders',[
				'uses' => "Tender\State\TenderStateController@adminUpdateManagerTender",
				'as'   => 'tender_state.adminUpdateManagerTender'
			]);
			Route::get('manager/tender_state/delete/{id}',[
				'uses' => "Tender\State\TenderStateController@adminDeleteManagerTender",
				'as'   => 'tender_state.adminDeleteManagerTender'
			]);

			Route::get('tender_state/create', [
				'uses' => "Tender\State\TenderStateController@create",
				'as'   => 'tender_state.index'
			]);

			Route::get('tender_state/create/4', [
				'uses' => "Tender\State\TenderStateController@create",
				'as'   => 'tender_state.create4'
			]);

			Route::get('tender_state/{type}', [
				'uses' => "Tender\State\TenderStateController@index",
				'as'   => 'tender_state.index4'
			]);

			Route::post('remove/parsers/by/id',[
				'uses' => "Tender\State\ParserStateController@removeParsersById",
				'as'   => 'tender_state_parser.removeParsersById'
			]);

			Route::resource('tender_state','Tender\State\TenderStateController');

			Route::post('statistics/setCpvStatistics', [
				'uses' => "Statistics\StatisticsController@setCpvStatistics",
				'as'   => 'statistics.setCpvStatistics'
			]);

			Route::post('statistics/mergeCpvStatistics', [
				'uses' => "Statistics\StatisticsController@mergeCpvStatistics",
				'as'   => 'statistics.mergeCpvStatistics'
			]);

			Route::post('statistics/updateCpvStatistics/{id}', [
				'uses' => "Statistics\StatisticsController@updateCpvStatistics",
				'as'   => 'statistics.updateCpvStatistics'
			]);

			Route::post('statistics/getCpvStatistics/{specification_id}', [
				'uses' => "Statistics\StatisticsController@getCpvStatistics",
				'as'   => 'statistics.getCpvStatistics'
			]);

			Route::post('statistics/setCpvPotential/{cpv_id}', [
				'uses' => "Statistics\StatisticsController@setCpvPotential",
				'as'   => 'statistics.getCpvStatistics'
			]);

			Route::post('statistics/searchParticipantName', [
				'uses' => "Statistics\StatisticsController@searchParticipantName",
				'as'   => 'statistics.getCpvStatistics'
			]);

	});

});

