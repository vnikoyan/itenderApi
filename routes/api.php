<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;

// Route::get('test', function () {
//     // event(new App\Events\Test());
//     return "Event has been sent!";
// });
// Route::get('/', function() {
//     return 'Hello World';
// });
// Route::group([
//     'prefix' => 'v1',
//     'namespace' => 'Api'
// ],
Route::prefix('v1')->namespace('Api')->group(function () {
        Route::get('procurement-plan/getFilePdf/{procurement_id}', 'Procurement\ProcurementPlanController@getFilePdf');
        Route::get('checkAuth', 'User\UserController@checkAuth');
        Route::post('test-notification', 'User\UserController@testNotification');
        // Route::get('/', function () {
        //     return 'Hello World';
        // });
        Route::group(['middleware' => []], function () {
            // User Sign Up
            Route::post('auth/signup', 'Auth\AuthController@createUser');
            // User Sign In with Facebook
            Route::post('auth/facebook', 'Auth\AuthController@facebookConnect');
            // User Login
            Route::post('auth/login', 'Auth\AuthController@login');
            Route::post('auth/logout', 'Auth\AuthController@logout');
            Route::post('auth/isLoggedIn', 'Auth\AuthController@isLoggedIn');
            
            // Username Exists
            Route::get('username', 'Auth\AuthController@username');
            // User Forgot Password
            Route::post('auth/forgot/', 'Auth\AuthController@forgot');
            //User sent forgot password link
            Route::post('/forgot/password/link', 'Mail\MailController@ForgotPasswordLink');
            // forgot password page
            Route::post('/forgot/password/', 'Auth\AuthController@resetPassword');
            // activate account via email
            Route::post('user/account/activate', 'Mail\MailController@accountActivate');
            // Route::post('sendEmail', 'Auth\AuthController@sendEmail');

            // event get list
            Route::get('event', 'Settings\EventController@index');
            // event get by id
            Route::get('event/{id}', 'Settings\EventController@show');

            Route::get('info', 'Settings\InfoController@index');
            Route::get('faq', 'Settings\FaqController@index');
            Route::get('protest', 'Settings\ProtestController@index');

            Route::get('package', 'Settings\PackageController@index');

            Route::get('package/state', 'Settings\PackageController@getPackageState');
            
            // package co-workers list
            Route::get('co-workers', 'Settings\CoWorkersController@index');

            // Route::get('tender', 'Tender\TenderController@index');

            Route::get('contract/getByClient', 'Contract\ContractsController@getByClient');

            Route::get('tender/landing', 'Tender\TenderController@getLandingTenders');

            Route::get('tender/filterOptions', 'Tender\TenderController@getFilterOptions');

            Route::get('tender/getByTenderId/{tenderId}', 'Tender\TenderController@getByTenderId');
            Route::get('tender/getTenderRows/{tenderId}', 'Tender\TenderController@getTenderRows');

            Route::resource('tender', 'Tender\TenderController');

            Route::post('numberToWord','Tender\TenderController@numberToWord');
            Route::post('numberToWordArray','Tender\TenderController@numberToWordArray');
            
            Route::post('news/subscription','Settings\EventController@newsSubscription');
            Route::post('news/unsubscribe','Settings\EventController@newsUnsubscription');
        });
        // authorized
        Route::group(['middleware' => ['authorized']], function () {
            // Get User



            Route::post('user/responsible', 'User\UserController@createStateResponsibleUser');
            Route::put('user/responsible/{id}', 'User\UserController@updateResponsibleUser');
            Route::get('user/responsible', 'User\UserController@getResponsibleUser');
            Route::get('user/package', 'User\UserController@getPackage');


            Route::get('user/root-user', 'User\UserController@getRootUser');

            Route::get('user/user-child', 'User\UserController@getUserChild');
            Route::get('user/menu-notifications', 'User\UserController@getMenuNotifications');
            Route::get('user/notifications', 'Notifications\NotificationsController@getNotifications');
            Route::post('user/notifications/read/{id}', 'Notifications\NotificationsController@read');
            Route::post('user/notifications/delete-all', 'Notifications\NotificationsController@deleteAll');

            Route::get('user/user-child-members', 'User\UserController@getUserChildMembers');

            Route::post('user/user-child-members/{id}', 'User\UserController@postUserChildMembers');

            Route::put('user/members/{id}', 'User\UserController@putUserChildMembers');

            Route::delete('user/members/{id}', 'User\UserController@deleteUserChildMembers');

            Route::get('user/user-group', 'User\UserController@getUserGrup');
            Route::get('user/search', 'User\UserController@search');
            Route::get('search/by/tin','User\UserController@searchByTin');

            Route::get('user/{user_id}', 'User\UserController@show');

            Route::resource('real-beneficiaries', 'User\RealBeneficiariesController');

            Route::get('me', 'User\UserController@me');
            Route::get('getRealBeneficiariesDeclaration', 'User\UserController@getRealBeneficiariesDeclaration');


            Route::put('user', 'User\UserController@edit');
            Route::put('private-user', 'User\UserController@editPrivateUser');
            Route::post('user/connect-telegram', 'User\UserController@connectTelegram');
            Route::put('organisation', 'User\UserController@editOrganisation');
            Route::put('user-password', 'User\UserController@editPassword');
            Route::get('cpv/search', 'Cpv\CpvController@search');
            Route::post('cpv/searchArray', 'Cpv\CpvController@searchArray');

            Route::get('cpv/{type}', 'Cpv\CpvController@index');

            
            Route::post('cpv/getCpvChildIds/{id}', 'Cpv\CpvController@getCpvChildIds');
            Route::post('cpv/getCpvByIds', 'Cpv\CpvController@getCpvByIds');
            Route::get('cpv/get_by_id/{id}', 'Cpv\CpvController@show');
            Route::get('cpv/specifications/{id}', 'Cpv\CpvController@getSpecificationsByCpvId');
            Route::post('cpv/specifications/{id}', 'Cpv\CpvController@setSpecificationsByCpvId');
            Route::put('cpv/specifications/{id}', 'Cpv\CpvController@updateSpecificationsByCpvId');

            // Statistics

            Route::get('statistics/getSpecifications/{cpv_id}', 'Statistics\ApiStatisticsController@getSpecifications');
            Route::post('statistics/getCpvStatistics/{specification_id}', 'Statistics\ApiStatisticsController@getCpvStatistics');
            Route::post('statistics/getFilterDatas/{specification_id}', 'Statistics\ApiStatisticsController@getFilterDatas');

            Route::get('categories/search', 'Categories\CategoriesController@search');
            Route::get('categories/{type}', 'Categories\CategoriesController@index');
            Route::get('categories/get_by_id/{id}', 'Categories\CategoriesController@show');

            Route::get('units', 'Settings\UnitsController@index');

            Route::get('applications/get/{tender_id}', 'Tender\ApplicationsController@get');
            Route::post('applications/set/{tender_id}', 'Tender\ApplicationsController@set');
        
            Route::get('classifier', 'Settings\ClassifierController@index');
            Route::get('financialClassifier', 'Settings\FinancialClassifierController@index');

            Route::get('classifier/{cpv_id}', 'Settings\ClassifierController@getByCpvId');


            Route::put('procurement/approve/{id}', 'Procurement\ProcurementController@approve');
            Route::post('procurement/uploadFile/{id}', 'Procurement\ProcurementController@uploadFile');
            Route::resource('procurement', 'Procurement\ProcurementController');

            Route::get('procurement-plan/getByCpvGroup/{procurement_id}/{cpv_type}', 'Procurement\ProcurementPlanController@getCpvGroup');
            Route::get('procurement-plan/getListByCpvGroup/{procurement_id}/{cpv_type}/{cpv_group}', 'Procurement\ProcurementPlanController@getListByCpvGroup');

            Route::get('procurement-plan/getByCpvType/{procurement_id}/{getByCpvType}', 'Procurement\ProcurementPlanController@getByCpvType');
            Route::get('procurement-plan/getByFinancialClassifiers/{procurement_id}/{financial_classifier_id}/{type}', 'Procurement\ProcurementPlanController@getByFinancialClassifiers');

            Route::get('procurement-plan/getByClassifiers/{procurement_id}/{classifier_id}/{type}', 'Procurement\ProcurementPlanController@getByClassifiers');

            Route::get('procurement-plan/getByQuery/{procurement_id}', 'Procurement\ProcurementPlanController@getByQuery');


            Route::get('procurement-plan/getByClassifiersForFinancial/{procurement_id}/{classifier_id}', 'Procurement\ProcurementPlanController@getByClassifiersForFinancial');
            Route::get('procurement-plan/getByClassifiersForFinancialCpvType/{procurement_id}/{classifier_id}/{financial_classifier}', 'Procurement\ProcurementPlanController@getByClassifiersForFinancialCpvType');
            Route::get('procurement-plan/getProcurementByClassifierIdFinancialId/{procurement_id}/{classifier_id}/{financial_classifier}/{cpv_type}', 'Procurement\ProcurementPlanController@getProcurementByClassifierIdFinancialId');

            Route::get('procurement-plan/getByFinancialClassifierId/{procurement_id}', 'Procurement\ProcurementPlanController@getByFinancialClassifierId');
            Route::get('procurement-plan/getByFinancialClassifierCuntCpvType/{procurement_id}/{financial_classifier}', 'Procurement\ProcurementPlanController@getByFinancialClassifierCuntCpvType');



            Route::get('procurement-plan/getPlanByOrganize/{procurement_id}', 'Procurement\ProcurementPlanController@getPlanByOrganize');
            Route::get('procurement-plan/getByClassifierId/{procurement_id}', 'Procurement\ProcurementPlanController@getByClassifierId');



            Route::get('procurement-plan/showDataTable/{id}', 'Procurement\ProcurementPlanController@showDataTable');

            Route::put('procurement-plan/update-multi', 'Procurement\ProcurementPlanController@updateMulti');
            Route::put('procurement-plan/status/{row_id}', 'Procurement\ProcurementPlanController@updateStatus');
            Route::put('procurement-plan/editDetails/{details_id}', 'Procurement\ProcurementPlanController@editDetails');

            Route::post('procurement-plan/storeDetails/{procurement_id}', 'Procurement\ProcurementPlanController@storeDetails');

            Route::get('procurement-plan/histories/{row_id}', 'Procurement\ProcurementPlanController@getHistories');
            Route::get('procurement-plan/histories-details/{details_id}', 'Procurement\ProcurementPlanController@getHistoriesDetails');
            Route::get('procurement-plan/getValidType/{id}/{cpv_id}', 'Procurement\ProcurementPlanController@getValidType');
            Route::get('procurement-plan/procurement-histories/{row_id}', 'Procurement\ProcurementPlanController@getHistoriesProcurementId');

            Route::resource('procurement-plan', 'Procurement\ProcurementPlanController');


            // COMPETITIVE
            Route::get('organize/get', 'Organize\OrganizeController@getAll');
            Route::get('organize/getByUser', 'Organize\OrganizeController@getByUser');
            Route::get('organize/getAllData/{id}', 'Organize\OrganizeController@getAllData');
            Route::put('organize/updateMulti', 'Organize\OrganizeController@updateMulti');
            Route::post('organize/contractFile/{organize_id}', 'Organize\OrganizeController@contractFile');
            Route::get('organize/cancel/{id}', 'Organize\OrganizeController@cancel');

            Route::resource('organize', 'Organize\OrganizeController');

            // ONE PERSON
            Route::get('organize/oneperson/get', 'Organize\OrganizeOnePersonController@getAll');
            Route::get('organize/oneperson/getByUser', 'Organize\OrganizeOnePersonController@getByUser');
            Route::get('organize/oneperson/getAllData/{id}', 'Organize\OrganizeOnePersonController@getAllData');
            Route::put('organize/oneperson/updateMulti', 'Organize\OrganizeOnePersonController@updateMulti');
            Route::post('organize-oneperson/contractFile/{organize_id}', 'Organize\OrganizeOnePersonController@contractFile');
            Route::post('organize-oneperson/uploadInvoiceFile/{organize_id}', 'Organize\OrganizeOnePersonController@uploadInvoiceFile');
            Route::post('organize-oneperson/uploadRowsFile/{organize_id}', 'Organize\OrganizeOnePersonController@uploadRowsFile');
            Route::post('organize-oneperson/getRowsFile/{organize_id}', 'Organize\OrganizeOnePersonController@getRowsFile');
            Route::get('organize/oneperson/cancel/{id}', 'Organize\OrganizeOnePersonController@cancel');

            Route::resource('organize/oneperson', 'Organize\OrganizeOnePersonController');

            // ITENDER
            Route::get('organize/itender/get', 'Organize\OrganizeItenderController@getAll');
            Route::get('organize/itender/getByUser', 'Organize\OrganizeItenderController@getByUser');
            Route::get('organize/itender/getAllData/{id}', 'Organize\OrganizeItenderController@getAllData');
            Route::put('organize/itender/updateMulti', 'Organize\OrganizeItenderController@updateMulti');
            Route::post('organize-itender/contractFile/{organize_id}', 'Organize\OrganizeItenderController@contractFile');
            Route::post('organize/itender/cancel/{id}', 'Organize\OrganizeItenderController@cancel');
            Route::post('organize/itender/evalution/{id}', 'Organize\OrganizeItenderController@evalution');
            Route::post('organize/itender/setWinner/{id}', 'Organize\OrganizeItenderController@setWinner');
            Route::post('organize/itender/uploadAdditionalFile/{id}', 'Organize\OrganizeItenderController@uploadAdditionalFile');
            Route::post('organize/itender/report/{id}', 'Organize\OrganizeItenderController@setReportDocument');

            Route::resource('organize/itender', 'Organize\OrganizeItenderController');

            Route::get('organize-row/getByOrganize/{organize_id}', 'Organize\OrganizeRowController@getByOrganize');
            Route::get('organize-row/getByOrganizeParticipmants/{organize_id}', 'Organize\OrganizeRowController@getByOrganizeParticipmants');
            Route::post('organize-row-array', 'Organize\OrganizeRowController@storeArray');
            Route::post('organize-row-array/numbering', 'Organize\OrganizeRowController@numbering');
            Route::post('organize-row-array/fromExcel', 'Organize\OrganizeRowController@storeArrayFromExcel');
            Route::post('organize-row/autoInsertPercents', 'Organize\OrganizeRowController@autoInsertPercents');
            Route::resource('organize-row', 'Organize\OrganizeRowController');
            Route::post('organize-row/deleteArray', 'Organize\OrganizeRowController@deleteArray');
            Route::post('organize-row/setWinnersForLots', 'Organize\OrganizeRowController@setWinnersForLots');
            Route::post('organize-row/updateInfo/{id}', 'Organize\OrganizeRowController@updateInfo');

            Route::get('organize-row-percent/byOrganize/{organize_id}', 'Organize\OrganizeRowPercentController@organizeRow');

            Route::resource('organize-row-percent', 'Organize\OrganizeRowPercentController');

            Route::get('participant/getByGroupId/{group_id}', 'Participant\ParticipantController@getByGroupId');
            Route::get('participant/getByOrganize/{organize_row}', 'Participant\ParticipantController@getByOrganize');
            Route::get('participant-group/getByOrganize/{organize_row}', 'Participant\ParticipantGroupController@show');
            Route::get('participant-group/getByOrganize/getWonLots/{organize_row}', 'Participant\ParticipantGroupController@getWonLots');
            Route::post('participant-group/addPersonalInfo/{id}', 'Participant\ParticipantGroupController@addPersonalInfo');
            Route::post('participant-group/processXML', 'Participant\ParticipantGroupController@processXML');
            Route::resource('participant', 'Participant\ParticipantController');
            Route::post("participant-group/saveContractDocument","Participant\ParticipantGroupController@saveContractDocument");
            Route::post("participant-group/createInvoiceParticipantGroup","Participant\ParticipantGroupController@createInvoiceParticipantGroup");
            Route::resource('participant-group', 'Participant\ParticipantGroupController');

            Route::get('participant-row/getByOrganizeRowId/{organize_row_id}', 'Participant\ParticipantRowController@getByOrganizeRowId');
            Route::get('participant-row/getWinnerByOrganizeRowId/{organize_row_id}', 'Participant\ParticipantRowController@getWinnerByOrganizeRowId');
            Route::get('participant-row/histories/{row_id}', 'Participant\ParticipantRowController@getHistories');
            Route::post('participant-row/checkEqualPrice/{row_id}', 'Participant\ParticipantRowController@checkEqualPrice');

            Route::resource('participant-row', 'Participant\ParticipantRowController');

            Route::get('contract/getByOrganize/{organize_id}', 'Contract\ContractsController@getByOrganize');
            Route::get('contract/getByClient', 'Contract\ContractsController@getByClient');
            Route::get('contract/getByProvider', 'Contract\ContractsController@getByProvider');
            Route::get('contract/getRequestsByProvider', 'Contract\ContractsController@getRequestsByProvider');
            Route::post('contract/fromApplication', 'Contract\ContractsController@fromApplication');
            Route::post('contract/fromApplication/complete', 'Contract\ContractsController@fromApplicationComplete');

            Route::resource('contract', 'Contract\ContractsController');
            Route::resource('contract-lots', 'Contract\ContractLotsController');

            Route::get('contract-orders/getByProvider', 'Contract\ContractOrdersController@getByProvider');
            Route::get('contract-orders/cancel/{order_id}', 'Contract\ContractOrdersController@cancel');
            Route::resource('contract-orders', 'Contract\ContractOrdersController');

            Route::get('suggestions/getByOrganizeId/{id}', 'Suggestions\SuggestionsController@getByOrganizeId');
            Route::get('suggestions/get', 'Suggestions\SuggestionsController@getAll');
            Route::get('suggestions/getByProvider/{organize_id}', 'Suggestions\SuggestionsController@show');
            Route::post('suggestions/create', 'Suggestions\SuggestionsController@create');
            Route::post('suggestions/cancel/{id}', 'Suggestions\SuggestionsController@cancel');
            Route::post('suggestions/favorite/{id}','Suggestions\SuggestionsController@favoriteSuggestion');
            Route::post('suggestions/uploadAdditionalFile/{id}', 'Suggestions\SuggestionsController@uploadAdditionalFile');
            Route::post('suggestions/deleteAdditionalFile/{id}', 'Suggestions\SuggestionsController@deleteAdditionalFile');
            Route::resource('suggestions', 'Suggestions\SuggestionsController');

            Route::get('selected-participants/getByGroupId/{group_id}', 'Participant\SelectedParticipantController@getByGroupId');
            Route::resource('selected-participants', 'Participant\SelectedParticipantController');

            // Route::resource('user-cpvs', 'UserCategories\UserCpvsController');
            Route::post('user-cpvs/{user_id}', 'UserCategories\UserCpvsController@storeUserCpvs');
            Route::get('user-cpvs', 'UserCategories\UserCpvsController@getUserCpvs');
            Route::post('user-categories/{user_id}', 'UserCategories\UserCategoriesController@storeUserCategories');
            Route::get('user-categories', 'UserCategories\UserCategoriesController@getUserCategories');


            Route::get('purchasing-process/suggestions', 'PurchasingProcess\PurchasingProcessController@suggestions');
            Route::get('purchasing-process/notSuggestions', 'PurchasingProcess\PurchasingProcessController@notSuggestions');
            Route::post('purchasing-process/user/{id}', 'PurchasingProcess\PurchasingProcessController@storeUser');
            Route::delete('purchasing-process/user/{id}/{user_id}', 'PurchasingProcess\PurchasingProcessController@deleteUser');
            Route::get('purchasing-process/getByOrganisationId/{id}', 'PurchasingProcess\PurchasingProcessController@showByOrganisation');
            Route::resource('purchasing-process', 'PurchasingProcess\PurchasingProcessController');


            Route::get('purchasing-process-percent/getByPurchasingProcessId/{purchasing_process_id}', 'PurchasingProcess\PurchasingProcessPercentController@getByPurchasingProcessId');
            Route::resource('purchasing-process-percent', 'PurchasingProcess\PurchasingProcessPercentController');

            Route::post('tender/favorite','Favorite\FavoriteController@favoriteTender');
            Route::get('tender/get/favorite','Favorite\FavoriteController@getfavoriteTender');
            Route::resource('user-tenders', 'Tender\UserTendersController');

            Route::post("create/order/payment/url", "Settings\UserPaymentController@createOrderPaymentUrl");
            Route::post("create/order/state/payment/url", "Settings\UserPaymentController@createOrderStatePaymentUrl");
            Route::post("creat/order/bank/transfer","Settings\UserPaymentController@createOrderBankTransfer");

            Route::post("send/email/guarantee","Mail\MailController@sendGuarantee");

            Route::post("banner/click", "Settings\UserPaymentController@bannerClick");

            Route::resource('guide', 'Settings\GuideController');
            Route::resource('user-filters', 'Settings\UserFiltersController');
            Route::post("gold/package/probation/activate","Settings\UserPaymentController@goldPackageProbationActivate");
            
            Route::post("gold/package/probation/activate/order_state","Settings\UserPaymentController@goldPackageProbationActivateOrderState");

            Route::post("add/tenders/table/config","Tender\TenderController@addTendersTableConfig");
            
            Route::post("manager/add/tender","Tender\TenderController@managerAddTender");
            Route::post("manager/edit/tender","Tender\TenderController@managerEditTender");
            Route::get("manager/getTenderFormSelectValues","Tender\TenderController@getTenderFormSelectValues");
            Route::get("manager/get/tenders","Tender\TenderController@managerGetTender");
            Route::get("manager/managerGetTenderById/{tender_id}","Tender\TenderController@managerGetTenderById");
            Route::post("manager/delete/tender","Tender\TenderController@managerDeleteTender");
            

            Route::post("email/notification","Mail\MailController@mailNotifications");

            
        });
        
        Route::post("convert/string/to/html","Settings\PdfController@convertStringToHtml");
        
        Route::post("create/pdf/file","Settings\PdfController@createPdfFile");
        
        Route::get('madmini','Mail\MailController@Reminder');

        Route::get('site/info','Settings\InfoController@info');

        Route::post("parseRowsFromExel","Settings\InfoController@parseRowsFromExel");
        
        Route::get('getRegulation','Settings\RegulationController@get');

});
