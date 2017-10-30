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

Route::get('admin', 'SiteController@index');
Route::resource('admin/users', 'SuperAdminController');

Route::resource('admin/sites', 'SiteController');
Route::get('admin/sites/{site}/addUser', 'SiteController@createAddUser')
          ->name('sites.addUser');
Route::post('admin/sites/{site}/addUser', 'SiteController@storeUser')
          ->name('sites.storeUser');
Route::delete('admin/sites/{site}/deleteUser/{user}', 'SiteController@destroyUser');

Route::get('admin/login', 'publicController@login')
          ->name('admin.login');
Route::post('admin/login', 'customAuth@checkAuth');
Route::get('admin/logout', 'customAuth@logout')
          ->name('admin.logout');

Route::resource('{site}/settings/categories', 'CategoryController');
Route::resource('{site}/items', 'ItemController');
Route::resource('{site}/settings/admin', 'AdminController');
Route::resource('{site}/bookings', 'BookingsController');
Route::resource('{site}/templates', 'TemplateController');
Route::resource('{site}/internal', 'InternalEventController');
Route::resource('{site}/settings/style', 'StyleController');


Route::get('{site}/bookings/index/complete', 'BookingsController@indexComplete')
          ->name('bookings.complete');
Route::get('{site}/bookings/{id}/{nocache}/invoice', 'BookingsController@getInvoice')
          ->name('bookings.invoice');
Route::get('{site}/bookings/{id}/add', 'BookingsController@addItems')
          ->name('bookings.add');
Route::post('{site}/bookings/{id}/add', 'BookingsController@updateItems');
Route::post('{site}/bookings/changestate', 'BookingsController@changeState');
Route::get('{site}/bookings/{id}/submit', 'BookingsController@submitBooking')
          ->name('bookings.submit');
Route::patch('{site}/bookings/{booking}/updateStatus', 'BookingsController@updateStatus')
          ->name('bookings.updateStatus');


Route::post('{site}/settings/admin/save', 'AdminController@Save')
          ->name('admin.save');

Route::get('{site}/settings/content', 'ContentController@index')
          ->name('settings.content');
Route::post('{site}/settings/content/{page}', 'ContentController@getPage');
Route::patch('{site}/settings/content', 'ContentController@savePage');


Route::get('{site}/treasurer', 'treasurerController@index')
          ->name('bank.index');
Route::post('{site}/treasurer', 'treasurerController@submit')
          ->name('bank.submit');
Route::delete('{site}/treasurer/{booking}', 'treasurerController@vatSorted')
          ->name('bank.vatdone');


Route::get('{site}/', 'publicController@index')
          ->name('home');
Route::get('{site}/terms', 'publicController@terms')
          ->name('terms');

Route::get('{site}/login', 'publicController@login')
          ->name('login');

Route::post('{site}/login', 'customAuth@checkAuth');
Route::get('{site}/logout', 'customAuth@logout')
          ->name('logout');

Route::get('{site}/calendar/{type}', 'CalendarController@downloadCalendar');

Route::get('{site}/invoice_demo', 'AdminController@pdfTest')
          ->name('demoInvoice');
