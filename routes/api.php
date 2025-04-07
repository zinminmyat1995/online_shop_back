<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/user/login',[App\Http\Controllers\ApiControllers\UserRegistrationController::class,"login"]);
Route::post('/user/register',[App\Http\Controllers\ApiControllers\UserRegistrationController::class,"register"]);
Route::get('/user-list/search',[App\Http\Controllers\ApiControllers\UserListController::class,"search"]);
Route::post('/user-list/update',[App\Http\Controllers\ApiControllers\UserListController::class,"update"]);
Route::delete('/user-list/delete',[App\Http\Controllers\ApiControllers\UserListController::class,"delete"]);
Route::post('/user/reset-password',[App\Http\Controllers\ApiControllers\UserRegistrationController::class,"reset_password"]);

Route::post('/product-category/register',[App\Http\Controllers\ApiControllers\ProductRegistrationController::class,"addProductCategory"]);
Route::delete('/product-category/remove',[App\Http\Controllers\ApiControllers\ProductRegistrationController::class,"removeProductCategory"]);
Route::get('/product-category/search',[App\Http\Controllers\ApiControllers\ProductRegistrationController::class,"productCategorySearch"]);
Route::post('/product-registration/save',[App\Http\Controllers\ApiControllers\ProductRegistrationController::class,"save"]);
Route::get('/product-list/search',[App\Http\Controllers\ApiControllers\ProductListController::class,"search"]);
Route::post('/product-list/update',[App\Http\Controllers\ApiControllers\ProductListController::class,"update"]);
Route::delete('/product-list/delete',[App\Http\Controllers\ApiControllers\ProductListController::class,"delete"]);
Route::post('/import-registration/save',[App\Http\Controllers\ApiControllers\ImportRegistrationController::class,"save"]);

Route::post('/ng-registration/save',[App\Http\Controllers\ApiControllers\NGRegistrationController::class,"save"]);
Route::get('/ng-registration/search',[App\Http\Controllers\ApiControllers\NGRegistrationController::class,"search"]);
Route::get('/ng-list/search',[App\Http\Controllers\ApiControllers\NGListController::class,"search"]);
Route::post('/ng-list/update',[App\Http\Controllers\ApiControllers\NGListController::class,"update"]);
Route::delete('/ng-list/delete',[App\Http\Controllers\ApiControllers\NGListController::class,"delete"]);

Route::post('/ng-return-registration/save',[App\Http\Controllers\ApiControllers\NGReturnRegistrationController::class,"save"]);
Route::get('/ng-return-list/search',[App\Http\Controllers\ApiControllers\NGReturnListController::class,"search"]);
Route::delete('/ng-return-list/delete',[App\Http\Controllers\ApiControllers\NGReturnListController::class,"delete"]);

Route::get('/ng-arrive-registration/search',[App\Http\Controllers\ApiControllers\NGArriveRegistrationController::class,"searchAllData"]);
Route::post('/ng-arrive-registration/save',[App\Http\Controllers\ApiControllers\NGArriveRegistrationController::class,"save"]);
Route::get('/ng-arrive-list/search',[App\Http\Controllers\ApiControllers\NGArriveListController::class,"search"]);
Route::delete('/ng-arrive-list/delete',[App\Http\Controllers\ApiControllers\NGArriveListController::class,"delete"]);
Route::get('/ng-detail-information/search',[App\Http\Controllers\ApiControllers\NGDetailInformationController::class,"getDetailInformation"]);
Route::post('/ng-detail-information/approve',[App\Http\Controllers\ApiControllers\NGDetailInformationController::class,"approve"]);

Route::get('/setting/discount-search',[App\Http\Controllers\ApiControllers\SettingController::class,"discountSearch"]);
Route::post('/setting/discount-save',[App\Http\Controllers\ApiControllers\SettingController::class,"discountSave"]);
Route::post('/setting/payment-save',[App\Http\Controllers\ApiControllers\SettingController::class,"paymentSave"]);
Route::delete('/setting/payment-delete',[App\Http\Controllers\ApiControllers\SettingController::class,"paymentDelete"]);
Route::get('/setting/payment-search',[App\Http\Controllers\ApiControllers\SettingController::class,"paymentSearch"]);
Route::get('/setting/delivery-search',[App\Http\Controllers\ApiControllers\SettingController::class,"deliverySearch"]);
Route::post('/setting/delivery-save',[App\Http\Controllers\ApiControllers\SettingController::class,"deliverySave"]);
Route::get('/setting/print-search',[App\Http\Controllers\ApiControllers\SettingController::class,"printSearch"]);
Route::post('/setting/print-save',[App\Http\Controllers\ApiControllers\SettingController::class,"printSave"]);
Route::get('/setting/all-service',[App\Http\Controllers\ApiControllers\SettingController::class,"getAllService"]);

Route::get('/sale-registration/search-all-data',[App\Http\Controllers\ApiControllers\SaleRegistrationController::class,"searchAllData"]);
Route::post('/sale-registration/save',[App\Http\Controllers\ApiControllers\SaleRegistrationController::class,"save"]);
Route::get('/sale-list/search',[App\Http\Controllers\ApiControllers\SaleListController::class,"search"]);
Route::get('/sale-list/search-data',[App\Http\Controllers\ApiControllers\SaleListController::class,"searchData"]);


Route::get('/warehouse/instock-search',[App\Http\Controllers\ApiControllers\InstockController::class,"search"]);
Route::post('/warehouse/instock-update',[App\Http\Controllers\ApiControllers\InstockController::class,"update"]);
Route::delete('/warehouse/instock-delete',[App\Http\Controllers\ApiControllers\InstockController::class,"delete"]);

Route::get('/history/search',[App\Http\Controllers\ApiControllers\HistoryController::class,"search"]);

Route::get('/dashboard/all-data',[App\Http\Controllers\ApiControllers\DashboardController::class,"getAllData"]);