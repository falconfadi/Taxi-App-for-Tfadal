<?php


use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\OffersController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\RequestEditController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PointsController;
use App\Http\Controllers\MakeNotificationController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Auth\AdminLoginController;
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

Route::get('/config-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return '<h1>Clear Config and cache cleared</h1>';
});
Route::get('/route-cache', function() {
    Artisan::call('route:clear');
    return '<h1>route cleared</h1>';
});

//Auth::routes();
//website
Route::get('/',[\App\Http\Controllers\HomeController::class,'index']);
Route::get('/privacy',[\App\Http\Controllers\HomeController::class,'privacy']);
Route::get('/contact',[\App\Http\Controllers\ContactController::class,'index']);

Route::get('admin/login', 'AdminLoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'AdminLoginController@login')->name('admin.login.submit');


Route::group(['middleware' => ['auth:admin']], function() {

    Route::group(['prefix'=>'admin'],function(){
        Route::get('logout', 'AdminLoginController@logout')->name('admin.logout');
        Route::get('home', 'Admin\HomeController@index')->name('admin.home');
        Route::get('change_password', 'Admin\HomeController@changePassword');
        Route::post('change_password/store','Admin\HomeController@changePasswordStore');
        //cars
        Route::get('cars', 'Admin\CarController@index');
        Route::get('cars/edit/{id}', 'Admin\CarController@edit');
        Route::post('cars/update', 'Admin\CarController@update');
        Route::get('cars/delete/{id}', 'Admin\CarController@destroy');
        Route::get('car-types', 'Admin\CarController@carTypes');
        Route::post('car-types/store', 'Admin\CarController@storeCarTypes');
        Route::get('car-types/edit/{id}', 'Admin\CarController@editCarTypes');
        Route::post('car-types/update', 'Admin\CarController@updateCarTypes');
        Route::get('car-types/delete/{id}', 'Admin\CarController@destroyCarTypes');
        //ajax
        Route::get('checkRelatedDrivers', 'Admin\CarController@checkRelatedDrivers')->name('drivers.check');

        Route::get('car-models', 'Admin\CarModelController@index');
        Route::post('car-models/store', 'Admin\CarModelController@store');
        Route::get('car-models/edit/{id}', 'Admin\CarModelController@edit');
        Route::post('car-models/update', 'Admin\CarModelController@update');
        Route::get('car-models/delete/{id}', 'Admin\CarModelController@destroy');

        //colors
        Route::get('colors', 'Admin\ColorsController@index');
        Route::post('colors/store', 'Admin\ColorsController@store');
        Route::get('colors/delete/{id}', 'Admin\ColorsController@destroy');

        //drivers
        Route::get('drivers', 'Admin\DriverController@index');
        Route::get('drivers/verify/{id}', 'Admin\DriverController@verify');
        Route::get('drivers/unverify/{id}', 'Admin\DriverController@unVerify');
        Route::post('drivers/freeze/', 'Admin\DriverController@freeze');
        Route::get('drivers/unfreeze/{id}', 'Admin\DriverController@unfreeze');
        Route::get('drivers/view/{id}', 'Admin\DriverController@view');
        Route::get('drivers/edit/{id}', 'Admin\DriverController@edit');
        Route::post('drivers/update', 'Admin\DriverController@update');
        Route::get('drivers/delete/{id}', 'Admin\DriverController@destroy');
        Route::get('drivers_have_trips', 'Admin\DriverController@driversHaveTrips');
        Route::get('drivers/final_delete/{id}', 'Admin\DriverController@finalDelete');
        Route::get('drivers/change_password/{id}', 'Admin\DriverController@changePassword');
        Route::post('drivers/change_password', 'Admin\DriverController@changePasswordUpdate');
        Route::get('drivers_map', 'Admin\DriverController@driversOnMap');
        Route::get('search-drivers', [\App\Http\Controllers\Admin\DriverController::class,'search'])->name('driver.search');
        Route::post('drivers/add_career/', 'Admin\DriverController@addCareer');
        Route::post('drivers/delete_token/', 'Admin\DriverController@deleteToken');
        Route::get('driver_trips/{id}', 'Admin\DriverController@driverTrips');


        //users
        //Route::get('users', 'Admin\UserController@index')->name('users.index');
        Route::get('users', 'Admin\UserController@index');

        Route::get('users/view/{id}', 'Admin\UserController@view');
        Route::get('users/edit/{id}', 'Admin\UserController@edit');
        Route::post('users/update', 'Admin\UserController@update');
        Route::get('users/delete/{id}', 'Admin\UserController@destroy');
        Route::get('users/change_password/{id}', 'Admin\UserController@changePassword');
        Route::post('users/change_password', 'Admin\UserController@changePasswordUpdate');
        Route::post('users/add_note', 'Admin\UserController@addNote');

        Route::post('users/freeze/', 'Admin\UserController@freeze');
        Route::get('users/unfreeze/{id}', 'Admin\UserController@unfreeze');
        Route::get('users/final_delete/{id}', 'Admin\UserController@finalDelete');
        Route::get('search-users', [\App\Http\Controllers\Admin\UserController::class,'search'])->name('user.search');
        Route::post('users/add_career/', 'Admin\UserController@addCareer');
        Route::get('user_trips/{id}', 'Admin\UserController@userTrips');

        //notifications admin
        Route::get('notifications', [App\Http\Controllers\Admin\NotificationsController::class, 'index']);
        Route::get('drivers_notifications', [App\Http\Controllers\Admin\NotificationsController::class, 'driversNotifications']);
        Route::get('notifications/delete/{id}', [App\Http\Controllers\Admin\NotificationsController::class, 'destroy']);
        Route::post('notifications/store', [App\Http\Controllers\Admin\NotificationsController::class, 'sendNotification']);
        Route::post('notifications/drivers_store', [App\Http\Controllers\Admin\NotificationsController::class, 'sendNotificationDrivers']);

        //trips
        Route::get('trips', [\App\Http\Controllers\Admin\TripController::class,'index']);
        Route::get('trips/create', [\App\Http\Controllers\Admin\TripController::class,'create']);
        Route::post('trips/store', [\App\Http\Controllers\Admin\TripController::class,'store']);
        Route::get('trips/show_trip_price', [\App\Http\Controllers\Admin\TripController::class,'showPrice'])->name('show_trip_price');
        Route::get('canceled_trips', [\App\Http\Controllers\Admin\TripController::class,'canceled_trips']);
        Route::get('active_trips', [\App\Http\Controllers\Admin\TripController::class,'active_trips']);
        Route::get('trips/view/{id}', [\App\Http\Controllers\Admin\TripController::class,'show']);
        Route::post('alert_driver/', [\App\Http\Controllers\Admin\TripController::class,'alertDriver']);
        Route::post('trips/cancel', [\App\Http\Controllers\Admin\TripController::class,'cancelTrip']);
        Route::post('trips/add_note', [\App\Http\Controllers\Admin\TripController::class,'addNote']);
        Route::post('trips/edit_note', [\App\Http\Controllers\Admin\TripController::class,'editNote']);
        Route::get('trip/{id}', [\App\Http\Controllers\Admin\TripController::class,'get_trip']);
        Route::get('trip/delete/{id}', [\App\Http\Controllers\Admin\TripController::class,'destroy']);
        Route::get('delete_testing_trips/{serial}', [\App\Http\Controllers\Admin\TripController::class,'deleteAllTripDetails']);

        Route::get('trips/search', [\App\Http\Controllers\Admin\TripController::class,'search'])->name('trip.search');
        Route::get('pending_trips', [\App\Http\Controllers\Admin\TripController::class,'pendingTrips']);
        Route::get('scheduled_trips', [\App\Http\Controllers\Admin\TripController::class,'scheduledTrips']);
        Route::post('trips/add_driver', [\App\Http\Controllers\Admin\TripController::class,'addDriver']);
        Route::post('trips/end_trip', [\App\Http\Controllers\Admin\TripController::class,'endTrip']);
        Route::post('trips/transform_captain', [\App\Http\Controllers\Admin\TripController::class,'transformCaptain']);

        //offers
        Route::get('offers', [OffersController::class,'index']);
        Route::post('offers/store', [OffersController::class,'store']);
        Route::get('offers/delete/{id}', [OffersController::class,'destroy']);
        Route::get('offers/edit/{id}', [OffersController::class,'edit']);
        Route::post('offers/update', [OffersController::class,'update']);
        Route::get('points', [PointController::class,'index']);

        Route::get('cities/', [CitiesController::class, 'index']);
        Route::post('cities/store', [CitiesController::class, 'store']);
        Route::get('cities/edit/{id}', [CitiesController::class,'edit']);
        Route::post('cities/update', [CitiesController::class,'update']);
        Route::get('cities/delete/{id}', [CitiesController::class,'destroy']);

        //companies
        //[admin]
        Route::get('companies/', [\App\Http\Controllers\Admin\CompaniesController::class, 'index']);
        Route::get('companies/create/', [\App\Http\Controllers\Admin\CompaniesController::class, 'create']);
        Route::post('companies/store', [\App\Http\Controllers\Admin\CompaniesController::class, 'store']);
        Route::get('companies/edit/{id}', [\App\Http\Controllers\Admin\CompaniesController::class,'edit']);

        //alerts
        Route::get('alerts',[\App\Http\Controllers\Admin\AlertController::class,'index']);
        Route::get('alerts/create',[\App\Http\Controllers\Admin\AlertController::class,'create']);
        Route::post('alerts/store', [\App\Http\Controllers\Admin\AlertController::class, 'store']);
        Route::get('alerts/edit/{id}',[\App\Http\Controllers\Admin\AlertController::class,'edit']);
        Route::post('alerts/update', [\App\Http\Controllers\Admin\AlertController::class, 'update']);
        Route::get('alerts/delete/{id}', [\App\Http\Controllers\Admin\AlertController::class,'destroy']);

        Route::get('verification_codes', [\App\Http\Controllers\Admin\VerificationCodeController::class,'index']);

        //permissions
        Route::get('permissions/', [\App\Http\Controllers\Admin\PermissionController::class, 'index']);
        Route::post('permissions/store', [\App\Http\Controllers\Admin\PermissionController::class, 'store']);
        Route::get('permissions/edit/{id}', [\App\Http\Controllers\Admin\PermissionController::class,'edit']);
        Route::post('permissions/update', [\App\Http\Controllers\Admin\PermissionController::class,'update']);
        Route::get('permissions/delete/{id}', [\App\Http\Controllers\Admin\PermissionController::class,'destroy']);

        Route::get('brands/', [\App\Http\Controllers\Admin\BrandController::class, 'index']);
        Route::post('brands/store', [\App\Http\Controllers\Admin\BrandController::class, 'store']);
        Route::get('brands/edit/{id}', [\App\Http\Controllers\Admin\BrandController::class,'edit']);
        Route::post('brands/update', [\App\Http\Controllers\Admin\BrandController::class,'update']);
        Route::get('brands/delete/{id}', [\App\Http\Controllers\Admin\BrandController::class,'destroy']);


        Route::get('driver-balance', [\App\Http\Controllers\Admin\BalanceController::class,'search'])->name('balance.search');
        Route::post('edit-balance', [\App\Http\Controllers\Admin\BalanceController::class,'editBalance']);

        Route::get('driver-compensation', [\App\Http\Controllers\Admin\DriverCompensationController::class,'index']);
        Route::get('update-compensation/{tripSerialNum}', [\App\Http\Controllers\Admin\DriverCompensationController::class,'edit']);
        Route::post('update-compensation', [\App\Http\Controllers\Admin\DriverCompensationController::class,'update']);



        //Route::get('pos', [\App\Http\Controllers\Admin\POSController::class, 'index']);
        Route::get('search-pos', [\App\Http\Controllers\Admin\POSController::class,'search'])->name('pos.search');


    });
    //notifications
    Route::get('/home-notification', [MakeNotificationController::class, 'index'])->name('home.noty');
    Route::patch('/fcm-token', [MakeNotificationController::class, 'updateToken'])->name('fcmToken');
    Route::get('/form', [MakeNotificationController::class, 'form']);
    Route::post('/send-notification',[MakeNotificationController::class,'notification'])->name('notification');

    //Reasons
    Route::get('admin/cancel_reasons',[\App\Http\Controllers\Admin\CancelReasonController::class,'index']);
    Route::get('admin/cancel_reasons/delete/{id}',[\App\Http\Controllers\Admin\CancelReasonController::class,'destroy']);
    Route::post('admin/cancel_reasons/store', [\App\Http\Controllers\Admin\CancelReasonController::class,'store']);
    Route::get('admin/cancel_reasons/edit/{id}', [\App\Http\Controllers\Admin\CancelReasonController::class,'edit']);
    Route::post('admin/cancel_reasons/update', [\App\Http\Controllers\Admin\CancelReasonController::class,'update']);

    Route::get('admin/feedback_reasons',[\App\Http\Controllers\Admin\FeedbackController::class,'index']);
    Route::get('admin/feedback_reasons/delete/{id}',[\App\Http\Controllers\Admin\FeedbackController::class,'destroy']);
    Route::post('admin/feedback_reasons/store', [\App\Http\Controllers\Admin\FeedbackController::class,'store']);
    Route::get('admin/feedback_reasons/edit/{id}', [\App\Http\Controllers\Admin\FeedbackController::class,'edit']);
    Route::post('admin/feedback_reasons/update', [\App\Http\Controllers\Admin\FeedbackController::class,'update']);

    //users kpi
    Route::get('admin/kpi_users',[\App\Http\Controllers\Admin\KpiUsersController::class,'index']);
    Route::get('admin/best_users',[\App\Http\Controllers\Admin\KpiUsersController::class,'best_users']);
    Route::get('admin/best_users_table',[\App\Http\Controllers\Admin\KpiUsersController::class,'best_users_table']);

    //drivers kpi
    Route::get('admin/kpi_drivers',[\App\Http\Controllers\Admin\KpiDriversController::class,'index']);
    Route::get('admin/best_drivers',[\App\Http\Controllers\Admin\KpiDriversController::class,'best_drivers']);
    Route::get('admin/best_drivers_table',[\App\Http\Controllers\Admin\KpiDriversController::class,'best_drivers_table']);

    Route::get('send-mobile/{id}',[\App\Http\Controllers\UserController::class,'sendNotificationrToUser']);

    //ajax
    Route::get('admin/getByBrandId/{id}', 'Admin\CarModelController@getByBrandId')->name('model.getByBrandId');

    //edit requests
    Route::get('admin/drivers/requests', [RequestEditController::class, 'index']);
    //money
    Route::get('admin/drivers/money', [\App\Http\Controllers\Admin\SumMoneyController::class, 'index']);
    Route::post('admin/drivers/money_per_month', [\App\Http\Controllers\Admin\SumMoneyController::class, 'sumMoneyAcheivedPerMonth']);
    Route::get('renew_balance_form', [\App\Http\Controllers\Admin\SumMoneyController::class, 'renewBalanceForm']);
    Route::post('admin/drivers/renew_balance', [\App\Http\Controllers\Admin\SumMoneyController::class, 'renew_balance']);

    //faqs
    Route::get('admin/faqs/', [\App\Http\Controllers\Admin\FaqController::class, 'index']);
    Route::post('admin/faqs/store', [\App\Http\Controllers\Admin\FaqController::class, 'store']);
    Route::get('admin/faqs/edit/{id}', [\App\Http\Controllers\Admin\FaqController::class,'edit']);
    Route::post('admin/faqs/update', [\App\Http\Controllers\Admin\FaqController::class,'update']);
    Route::get('admin/faqs/delete/{id}', [\App\Http\Controllers\Admin\FaqController::class,'destroy']);
    //alerts
    Route::get('admin/send-alerts/', [\App\Http\Controllers\Admin\DriverAlertController::class, 'index']);
    Route::post('admin/send-alerts/store', [\App\Http\Controllers\Admin\DriverAlertController::class, 'store']);


    //policy
    Route::get('admin/privacy_policy/', [\App\Http\Controllers\Admin\PolicyController::class, 'edit']);
    Route::post('admin/privacy_policy/update', [\App\Http\Controllers\Admin\PolicyController::class, 'update'])->name('privacy.edit');;

    Route::get('admin/who_we_are/', [\App\Http\Controllers\Admin\PolicyController::class, 'editWhoWeAre']);
    Route::post('admin/who_we_are/update', [\App\Http\Controllers\Admin\PolicyController::class, 'updateWhoWeAre']);

    Route::get('admin/borders/', [\App\Http\Controllers\Admin\BordersController::class, 'index']);
    Route::get('admin/add-markers/', [\App\Http\Controllers\Admin\BordersController::class, 'add_markers']);
    Route::get('admin/borders/edit/{id}', [\App\Http\Controllers\Admin\BordersController::class, 'edit']);
    Route::get('admin/borders/delete/{id}', [\App\Http\Controllers\Admin\BordersController::class,'destroy']);

    //ajax
    Route::post('store-markers', [\App\Http\Controllers\Admin\BordersController::class, 'storeMarkers']);
    Route::post('admin/borders/store', [\App\Http\Controllers\Admin\BordersController::class, 'storeArea']);
    Route::post('admin/borders/update', [\App\Http\Controllers\Admin\BordersController::class, 'updateArea']);
    Route::post('update-markers', [\App\Http\Controllers\Admin\BordersController::class, 'updateMarkers']);

    Route::get('getTimePlusQuarter', 'Api\CarController@getTimePlusQuarter1');

    //complaints'
    //Route::get('admin/complaints', [\App\Http\Controllers\Admin\ComplaintsController::class, 'index'])/*->name('complaints.index');*/;
    Route::get('admin/complaints', [\App\Http\Controllers\Admin\ComplaintsController::class, 'index'])->name('complaints.index');
    Route::get('admin/complaints/verify_and_send_alert/{id}', [\App\Http\Controllers\Admin\ComplaintsController::class, 'verifyAndSendAlert']);
    Route::post('admin/reply_complaints/store', [\App\Http\Controllers\Admin\ComplaintsController::class, 'store']);
    Route::get('admin/complaints/close/{id}', [\App\Http\Controllers\Admin\ComplaintsController::class, 'close']);

    //slider
    Route::get('admin/slider', [\App\Http\Controllers\Admin\SliderController::class, 'index']);
    Route::get('admin/slider/edit/{id}', [\App\Http\Controllers\Admin\SliderController::class,'edit']);
    Route::post('admin/slider/update', [\App\Http\Controllers\Admin\SliderController::class,'update']);
    Route::post('admin/slider/store', [\App\Http\Controllers\Admin\SliderController::class,'store']);
    Route::get('admin/slider/delete/{id}', [\App\Http\Controllers\Admin\SliderController::class,'destroy']);

    //sms
    Route::get('send_msg',[\App\Http\Controllers\Api\SendSMSController::class,'sendMsg']);
    Route::get('admin/send-sms/', [\App\Http\Controllers\Admin\SendSMSController::class, 'index']);
    Route::post('admin/send-sms/store', [\App\Http\Controllers\Admin\SendSMSController::class, 'store']);
    Route::post('admin/send-sms-users/store', [\App\Http\Controllers\Admin\SendSMSController::class, 'store_users']);
    Route::get('admin/send-sms1/', [\App\Http\Controllers\Admin\SendSMSController::class, 'sendSmsSyriatel']);
    Route::get('admin/send-sms/delete/{id}', [\App\Http\Controllers\Admin\SendSMSController::class,'destroy']);

    Route::get('admin/roles',[\App\Http\Controllers\Admin\RoleController::class,'index']);
    Route::get('admin/roles/create',[\App\Http\Controllers\Admin\RoleController::class,'create']);
    Route::post('admin/roles/store', [\App\Http\Controllers\Admin\RoleController::class, 'store']);
    Route::get('admin/roles/edit/{id}',[\App\Http\Controllers\Admin\RoleController::class,'edit']);
    Route::post('admin/roles/update', [\App\Http\Controllers\Admin\RoleController::class, 'update']);
    Route::get('admin/roles/delete/{id}', [\App\Http\Controllers\Admin\RoleController::class,'destroy']);

  //  Route::resource('admin/roles','Admin\RoleController');
    Route::get('admin/users_panel',[\App\Http\Controllers\Admin\RoleController::class,'users_panel']);
    Route::get('admin/users_panel/create',[\App\Http\Controllers\Admin\RoleController::class,'create_users_panel']);
    Route::post('admin/users_panel/store', [\App\Http\Controllers\Admin\RoleController::class, 'store_users_panel']);
    Route::get('admin/users_panel/edit/{id}',[\App\Http\Controllers\Admin\RoleController::class,'edit_users_panel']);
    Route::post('admin/users_panel/update', [\App\Http\Controllers\Admin\RoleController::class, 'update_users_panel']);
    Route::get('admin/users_panel/delete/{id}',[\App\Http\Controllers\Admin\RoleController::class,'destroy_users_panel']);


    Route::get('admin/settings', [\App\Http\Controllers\Admin\SettingController::class,'index'])->middleware('auth:admin');
    Route::get('admin/settings/edit', [\App\Http\Controllers\Admin\SettingController::class,'edit'])->middleware('auth:admin');
    Route::post('admin/settings/update', [\App\Http\Controllers\Admin\SettingController::class,'update']);
    Route::get('admin/settings/maintenance', [\App\Http\Controllers\Admin\SettingController::class,'maintenance_status']);
    Route::get('admin/settings/disactive_maintenance', [\App\Http\Controllers\Admin\SettingController::class,'disactive_maintenance_status']);


    //charts
    Route::get('users-charts',[\App\Http\Controllers\Admin\UsersChartController::class,'index'])->name('users.charts');

    //trips kpi
    Route::get('admin/kpi_trips',[\App\Http\Controllers\Admin\KpiTripController::class,'index'])->middleware('auth:admin');
    Route::get('admin/trips_form/{url}',[\App\Http\Controllers\Admin\KpiTripController::class,'trips_form'])->middleware('auth:admin');
    Route::post('admin/num_of_trips',[\App\Http\Controllers\Admin\KpiTripController::class,'num_of_trips'])->middleware('auth:admin');
    Route::post('admin/num_of_trips_cancelled',[\App\Http\Controllers\Admin\KpiTripController::class,'num_of_trips'])->middleware('auth:admin');
    Route::post('admin/trips_prob',[\App\Http\Controllers\Admin\KpiTripController::class,'trips_prob'])->middleware('auth:admin');
    Route::post('admin/trips_prob_cancelled',[\App\Http\Controllers\Admin\KpiTripController::class,'trips_prob_cancelled'])->middleware('auth:admin');
    Route::post('admin/trips_money',[\App\Http\Controllers\Admin\KpiTripController::class,'trips_money']);

    Route::get('admin/drawPath',[\App\Http\Controllers\Admin\CoordinateController::class,'drawPath']);
    //Log
    Route::get('admin/log',[\App\Http\Controllers\Admin\ActivityLogController::class,'index']);

    //cities
//    Route::get('admin/cities/', [CitiesController::class, 'index']);
//    //Route::get('admin/cities/', 'Admin\CitiesController@index');
//    Route::post('admin/cities/store', [CitiesController::class, 'store']);
//    Route::get('admin/cities/edit/{id}', [CitiesController::class,'edit']);
//    Route::post('admin/cities/update', [CitiesController::class,'update']);
//    Route::get('admin/cities/delete/{id}', [CitiesController::class,'destroy']);

    //regions
    Route::get('admin/regions/', [\App\Http\Controllers\Admin\RegionsController::class, 'index']);
    Route::post('admin/regions/store', [\App\Http\Controllers\Admin\RegionsController::class, 'store']);
    Route::get('admin/regions/edit/{id}', [\App\Http\Controllers\Admin\RegionsController::class,'edit']);
    Route::post('admin/regions/update', [\App\Http\Controllers\Admin\RegionsController::class,'update']);
    Route::get('admin/regions/delete/{id}', [\App\Http\Controllers\Admin\RegionsController::class,'destroy']);


    Route::get('admin/farway_regions/', [\App\Http\Controllers\Admin\FarwayRegionController::class, 'index']);
    Route::post('admin/farway_regions/store', [\App\Http\Controllers\Admin\FarwayRegionController::class, 'store']);
    Route::get('admin/farway_regions/edit/{id}', [\App\Http\Controllers\Admin\FarwayRegionController::class,'edit']);
    Route::post('admin/farway_regions/update', [\App\Http\Controllers\Admin\FarwayRegionController::class,'update']);
    Route::get('admin/farway_regions/delete/{id}', [\App\Http\Controllers\Admin\FarwayRegionController::class,'destroy']);

    //invoices
    Route::get('admin/invoices/', [\App\Http\Controllers\Admin\InvoiceController::class, 'index']);
    Route::post('admin/invoices/store', [\App\Http\Controllers\Admin\InvoiceController::class, 'store']);
    Route::get('admin/invoices/edit/{id}', [\App\Http\Controllers\Admin\InvoiceController::class,'edit']);
    Route::post('admin/invoices/update', [\App\Http\Controllers\Admin\InvoiceController::class,'update']);
    Route::get('admin/invoices/delete/{id}', [\App\Http\Controllers\Admin\InvoiceController::class,'destroy']);

    Route::get('admin/errors/', [\App\Http\Controllers\Admin\HandleErrorController::class, 'index']);
    Route::get('admin/errors/delete/{id}', [\App\Http\Controllers\Admin\HandleErrorController::class,'destroy']);


    //company
    //[company_admin]
    Route::get('company/edit/', [\App\Http\Controllers\Company\CompanyController::class, 'edit']);
    Route::post('company/update', [\App\Http\Controllers\Company\CompanyController::class, 'update']);
    Route::get('company/add_employees/', [\App\Http\Controllers\Company\CompanyController::class, 'addEmployees']);
    Route::get('company/employees', [\App\Http\Controllers\Company\CompanyController::class, 'index']);
    Route::get('company/delete/{id}', [\App\Http\Controllers\Company\CompanyController::class,'destroy']);
    Route::get('company/trips', [\App\Http\Controllers\Company\CompanyController::class,'trips']);
    Route::get('company/add_trip', [\App\Http\Controllers\Company\CompanyController::class,'addTrip']);
    Route::post('company/add_trip', [\App\Http\Controllers\Company\CompanyController::class,'addTripStore']);

    //ajax
    Route::get('company/search', [\App\Http\Controllers\Company\CompanyController::class,'searchByPhone'])->name('phone.search');
    Route::post('company/add_employee_store', [\App\Http\Controllers\Company\CompanyController::class, 'addEmployeeStore']);


    Route::get('admin/driver_by_id/{id}',[\App\Http\Controllers\Admin\DriverController::class,'getDriverByDriverId']);
});

Route::get('user-login',[\App\Http\Controllers\LoginUserController::class,'index']);
Route::post('user-login',[\App\Http\Controllers\LoginUserController::class,'login'])->name('user.login.submit');

Route::get('theme',[\App\Http\Controllers\admin\ThemeController::class,'index']);
Route::get('theme1',[\App\Http\Controllers\admin\ThemeController::class,'index1']);


Route::get('test0',[\App\Http\Controllers\Api\TestController::class,'test0']);
Route::get('test',[\App\Http\Controllers\Api\TestController::class,'test1']);
Route::get('test3',[\App\Http\Controllers\Admin\TripController::class,'test']);
//Route::get('test1',[\App\Http\Controllers\Admin\DriverController::class,'test']);
//Route::get('test2',[\App\Http\Controllers\Api\CarController::class,'test']);
//Route::get('test3',[\App\Http\Controllers\Api\TripController::class,'test']);
//Route::get('test4',[\App\Http\Controllers\PointLocationController::class,'test']);
//Route::get('test5',[\App\Http\Controllers\Admin\OffersController::class,'test']);
//Route::get('test6',[\App\Http\Controllers\Admin\RoleController::class,'test']);
//Route::get('test7',[\App\Http\Controllers\Admin\SendSMSController::class,'test']);
//Route::get('test8',[\App\Http\Controllers\Admin\NotificationsController::class,'test']);
Route::get('test9',[\App\Http\Controllers\Api\InvoiceController::class,'test']);
Route::get('test91',[\App\Http\Controllers\Api\InvoiceController::class,'test1']);
//Route::get('test10',[\App\Http\Controllers\Company\CompanyController::class,'test']);
//Route::get('test11',[\App\Http\Controllers\Admin\SumMoneyController::class,'test']);
//Route::get('test12',[\App\Http\Controllers\GeographicalController::class,'test']);
//Route::get('test13',[\App\Http\Controllers\Admin\UserController::class,'test']);
//Route::get('test14',[\App\Http\Controllers\Admin\AdminController::class,'test']);


//Route::get('/notifi', [App\Http\Controllers\TestNotificationController::class, 'index'])->name('notifi');
//Route::post('/save-token', [App\Http\Controllers\TestNotificationController::class, 'saveToken'])->name('save-token');
//Route::post('/send-notification', [App\Http\Controllers\TestNotificationController::class, 'sendNotification'])->name('send.notification');

