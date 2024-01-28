<?php


use App\Http\Controllers\Api\CarModelController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\FeedbakReasonController;
use App\Http\Controllers\Api\MultiTripController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\RequestEditController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\UserLocationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NoAuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\CarController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/get_user',[AuthController::class, 'getUser'] )->middleware('auth:sanctum');;

Route::post('user/login', [AuthController::class, 'login']);
Route::post('driver/login', [DriverController::class, 'driver_login']);

//Route::post('aass/login', function (){
//    $driver = \App\Models\Driver::find(1);
//    $token = $driver->createToken('authtoken');
//    return $token->plainTextToken;
//});
Route::post('send_new_varification_code', [AuthController::class, 'sendNewVerificationCode']);

Route::post('register_user', [AuthController::class, 'register']);
Route::post('forget-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);
Route::post('send_varification_code', [NewPasswordController::class, 'varification_code']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('user/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('user/update', [AuthController::class, 'update'])->middleware('auth:sanctum');
    Route::post('check_user', [AuthController::class, 'checkUser'])->middleware('auth:sanctum');
    Route::post('check_user_and_share_trip', [AuthController::class, 'checkUserAndShareTrip'])->middleware('auth:sanctum');

    //driver
    Route::post('change_online_status', [DriverController::class, 'changeOnlineStatus']);

    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');



    Route::post('change-password', [NewPasswordController::class, 'reset']);
    Route::post('change-phone', [NewPasswordController::class, 'change_phone']);
    //Route::get('get_balance_per_day', [DriverController::class, 'getBalancePerDay'])->middleware('auth:sanctum');


    Route::post('send_edit_car_request', [RequestEditController::class, 'sendEditCarRequest']);



    Route::post('rate_app', [\App\Http\Controllers\Api\RateController::class, 'rate_app']);

    Route::post('add-complaint', [\App\Http\Controllers\Api\ComplaintController::class, 'add_complaint']);
    Route::post('complaints_list', [\App\Http\Controllers\Api\ComplaintController::class, 'complaints_list']);
    Route::post('complaints_list_on_driver', [\App\Http\Controllers\Api\ComplaintController::class, 'complaints_list_on_driver']);
    Route::post('add_reply', [\App\Http\Controllers\Api\ComplaintController::class, 'add_reply']);
    Route::post('complaints_with_replies', [\App\Http\Controllers\Api\ComplaintController::class, 'complaints_with_replies']);

    //locations
    Route::post('user/my-places', [UserLocationsController::class, 'myPlaces']);
    Route::post('user/add-place', [UserLocationsController::class, 'store']);
    Route::post('user/edit-place', [UserLocationsController::class, 'update']);
    Route::post('user/delete-place', [UserLocationsController::class, 'delete']);

    //promotions + send_offer
    Route::post('promotions', [OffersController::class, 'promotions']);
    Route::post('add-promotion-code', [OffersController::class, 'add_promotion']);
    Route::post('send-offer', [OffersController::class, 'send_offer']);
    Route::post('my-offers', [OffersController::class, 'my_offers']);
    Route::post('get_by_code', [OffersController::class, 'get_by_code']);
    Route::post('insert_code', [OffersController::class, 'insert_code']);


    //notifications
    Route::post('user/get-notifications', [NotificationsController::class, 'getNotificationsToUser']);
    Route::post('user/delete-notification', [NotificationsController::class, 'deleteNotificationById']);
    Route::post('user/delete-all-notifications', [NotificationsController::class, 'deleteAllNotifications']);

    //trip
    Route::post('user/get_cars_type_with_value', [CarController::class, 'get_cars_type_with_value']);
    Route::post('add_new_trip', [TripController::class, 'add_new_trip']);
    Route::post('trip_details', [TripController::class, 'trip_details']);
    Route::post('get-trips-by-status', [TripController::class, 'getActiveTrips']);
    Route::post('get-trips-by-status-user', [TripController::class, 'getTripsByStatusToUser']);
    Route::post('approve_trip', [TripController::class, 'approve_trip']);
    Route::post('reject_trip', [TripController::class, 'reject_trip']);
    Route::post('cancel_trip', [TripController::class, 'cancel_trip']);
    Route::post('arrive_to_customer_location', [TripController::class, 'arrive_to_customer_location']);
    Route::post('start_trip', [TripController::class, 'start_trip']);
    Route::post('end_trip', [TripController::class, 'end_trip']);
    Route::post('rate_trip', [\App\Http\Controllers\Api\RateController::class, 'rate_trip']);
    Route::post('calculate_invoice', [TripController::class, 'calculate_invoice']);
    Route::post('add_schedule_trip', [TripController::class, 'add_schedule_trip']);
    Route::post('shared_trips', [TripController::class, 'sharedTrips']);
    Route::post('get_next_circle', [TripController::class, 'getNextCircle']);
    Route::post('trip_details_with_coordinates', [TripController::class, 'tripDetailswithCoordinates']);

    //multiple trips
    Route::post('add_trip_multi', [MultiTripController::class, 'add_trip_multi']);
    Route::post('get_cars_type_with_value_multi', [CarController::class, 'get_cars_type_with_value_multi']);
    Route::post('trip_details_multi', [MultiTripController::class, 'trip_details_multi']);
    Route::post('update_trip_multi', [MultiTripController::class, 'update_trip_multi']);

    Route::post('getActiveTripToDriver', [TripController::class, 'getActiveTripToDriver']);
    Route::post('getActiveTripToUser', [TripController::class, 'getActiveTripToUser']);
    Route::post('scheduled_trips', [TripController::class, 'scheduledTrips']);

    //driver
    Route::post('update_driver_location', [DriverController::class, 'update_location_api']);
    Route::post('driver/update', [DriverController::class, 'update']);

    //suggestions
    Route::post('add_suggestion', [\App\Http\Controllers\Api\SuggestionController::class, 'add_suggestion']);;
    Route::post('suggestions_list', [\App\Http\Controllers\Api\SuggestionController::class, 'suggestions_list']);

    //invoice
    Route::post('calculate_invoice', [\App\Http\Controllers\Api\InvoiceController::class, 'calculateInvoice']);
    Route::post('invoices_by_user', [\App\Http\Controllers\Api\InvoiceController::class, 'invoicesByUser']);
    Route::post('trip_with_invoice_details', [\App\Http\Controllers\Api\InvoiceController::class, 'tripwithInvoiceDetails']);


    //alerts
    Route::post('make_warning_read', [\App\Http\Controllers\Api\DriverAlertController::class, 'makeAlertSeen']);
    Route::post('alert_list', [\App\Http\Controllers\Api\DriverAlertController::class, 'alertList']);

    Route::post('sliders_with_offers', [\App\Http\Controllers\Api\SliderController::class, 'slidersWithOffers']);

    Route::post('update_counter', [\App\Http\Controllers\Api\CounterController::class, 'updateCounter']);
    Route::post('last_counter', [\App\Http\Controllers\Api\CounterController::class, 'lastCounter']);


    Route::post('my_balance_driver', [\App\Http\Controllers\Api\BalanceController::class, 'balanceByDriver']);




});

Route::post('forget-password-no-auth', [NewPasswordController::class, 'forgotPasswordNoAuth']);
Route::post('save-new-password', [NewPasswordController::class, 'saveNewPasswordNoAuth']);
Route::post('settings', [AuthController::class, 'settings']);



Route::get('faqs', [NoAuthController::class, 'faqs']);
Route::get('terms', [NoAuthController::class, 'terms']);
Route::get('cancel_trip_reasons', [NoAuthController::class, 'cancel_trip_reasons']);
Route::get('cars', [CarController::class, 'all_cars']);


Route::post('driver-register', [DriverController::class, 'register']);


//model & brand
Route::get('brands', [CarModelController::class, 'brands']);
Route::get('car-models', [CarModelController::class, 'car_models']);
Route::post('car-models-by-brand', [CarModelController::class, 'car_models_by_brand']);

//cars
Route::get('car-types', [CarController::class, 'car_types']);
Route::post('driver/update_car', [CarController::class, 'update'])->middleware('auth:sanctum');
//reasons to add complaint
Route::get('send_feedback_reason', [FeedbakReasonController::class, 'get_list']);

//Route::post('answer_alert', [\App\Http\Controllers\Api\DriverAlertController::class, 'answerAlert'])->middleware('auth:sanctum');

//point
//Route::post('get_user_points', [\App\Http\Controllers\Api\PointsController::class, 'getUserPoints'])->middleware('auth:sanctum');

//cities
Route::get('cities', [\App\Http\Controllers\Api\CityController::class, 'cities']);
Route::post('regions', [\App\Http\Controllers\Api\CityController::class, 'regions']);


//policies
Route::get('privacy_policy', [\App\Http\Controllers\Api\PolicyController::class, 'privacyPolicy']);
Route::get('who_we_are', [\App\Http\Controllers\Api\PolicyController::class, 'whoWeAre']);

Route::get('sliders', [\App\Http\Controllers\Api\SliderController::class, 'sliders']);
Route::post('handle-errors', [\App\Http\Controllers\Api\ErrorController::class, 'handleErrors']);

Route::get('testy',[\App\Http\Controllers\Admin\SendSMSController::class,'test']);


//syriatel payment
Route::get('get_syriatel_merchant_token', [\App\Http\Controllers\Api\SyriatelController::class, 'getSyriatelMerchantToken']);



