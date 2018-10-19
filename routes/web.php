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

// Super admin routes
Route::get('admin', 'SiteController@index');
Route::resource('admin/users', 'SuperAdminController');

Route::get('admin/email', 'SiteController@emailAll')->name('sites.email');
Route::get('admin/sites/restore', 'SiteController@restoreIndex')->name('sites.restore');
Route::delete('admin/sites/restore/{site}', 'SiteController@restore');
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

// Resource routes
Route::resource('{site}/settings/categories', 'CategoryController');
Route::resource('{site}/items', 'ItemController');
Route::resource('{site}/settings/admin', 'AdminController');
Route::resource('{site}/bookings', 'BookingsController');
Route::resource('{site}/templates', 'TemplateController');
Route::resource('{site}/internal', 'InternalEventController');
Route::resource('{site}/settings/style', 'StyleController');
Route::resource('{site}/settings/discounts', 'DiscountController');

// Booking routes
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


// Setting routes
Route::post('{site}/settings/admin/save', 'AdminController@Save')
          ->name('admin.save');

Route::get('{site}/settings/content', 'ContentController@index')
          ->name('settings.content');
Route::post('{site}/settings/content/{page}', 'ContentController@getPage');
Route::patch('{site}/settings/content', 'ContentController@savePage');

Route::get('{site}/settings/calendar', 'CalendarController@viewSettings')
          ->name('settings.calendar');
Route::post('{site}/settings/calendar/refreshAuth', 'CalendarController@updateAuth');


// Treasurer routes
Route::get('{site}/treasurer', 'treasurerController@index')
          ->name('bank.index');
Route::post('{site}/treasurer', 'treasurerController@submit')
          ->name('bank.submit');
Route::delete('{site}/treasurer/{booking}', 'treasurerController@vatSorted')
          ->name('bank.vatdone');


// public routes
Route::get('{site}/', 'publicController@index')
          ->name('home');
Route::get('{site}/terms', 'publicController@terms')
          ->name('terms');

Route::get('{site}/login', 'publicController@login')
          ->name('login');

Route::post('{site}/login', 'customAuth@checkAuth');
Route::get('{site}/logout', 'customAuth@logout')
          ->name('logout');


// PAT Routes
Route::get('{site}/pat/testing', 'patController@index')->name('pat.testing');
Route::post('{site}/pat/testing', 'patController@add')->name('pat.add');
Route::get('{site}/pat/testing/{item}', 'patController@recordIndex')->name('pat.newRecord');
Route::post('{site}/pat/testing/record', 'patController@record')->name('pat.record');
Route::get('{site}/pat/records', 'patController@exportCSV')->name('pat.records');



// other routes
Route::get('{site}/calendar/{auth}/{type}', 'CalendarController@downloadCalendar')
          ->name('calendar');

Route::get('{site}/invoice_demo', 'AdminController@pdfTest')
          ->name('demoInvoice');

// Files routes
Route::get('{site}/files/{file}', 'FilesController@download')->name('files.download');
Route::delete('{site}/files/{file}', 'FilesController@destroy');



Route::get('/', 'publicController@sitelessIndex');
