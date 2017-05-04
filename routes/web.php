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

/*Route::get('/', ['as' => 'home', function () {
    return view('home');
}]);*/

Route::resource('settings/categories', 'CategoryController');
Route::resource('items', 'ItemController');
Route::resource('settings/admin', 'AdminController');
Route::resource('bookings', 'BookingsController');
Route::resource('templates', 'TemplateController');
Route::resource('internal', 'InternalEventController');


Route::get('bookings/index/complete', 'BookingsController@indexComplete')
          ->name('bookings.complete');
Route::get('bookings/{id}/{nocache}/invoice', 'BookingsController@getInvoice')
          ->name('bookings.invoice');
Route::get('bookings/{id}/add', 'BookingsController@addItems')
          ->name('bookings.add');
Route::post('bookings/{id}/add', 'BookingsController@updateItems');
Route::post('bookings/changestate', 'BookingsController@changeState');
Route::get('bookings/{id}/submit', 'BookingsController@submitBooking')
          ->name('bookings.submit');
Route::patch('bookings/{booking}/updateStatus', 'BookingsController@updateStatus')
          ->name('bookings.updateStatus');


Route::post('settings/admin/save', 'AdminController@Save')
          ->name('admin.save');

Route::get('settings/content', 'ContentController@index')
          ->name('settings.content');
Route::post('settings/content/{page}', 'ContentController@getPage');
Route::patch('settings/content', 'ContentController@savePage');


Route::get('treasurer', 'treasurerController@index')
          ->name('bank.index');
Route::post('treasurer', 'treasurerController@submit')
          ->name('bank.submit');
Route::delete('treasurer/{booking}', 'treasurerController@vatSorted')
          ->name('bank.vatdone');


Route::get('/', 'publicController@index');
Route::get('terms', 'publicController@terms');

Route::get(
    '/login', ['as'=>'login',function () {
        return view('login');
    }]
);

Route::post('/login', 'customAuth@checkAuth');
Route::get('/logout', 'customAuth@logout');


// testing routes
Route::get('/calendar/{type}', 'CalendarController@downloadCalendar');
Route::get('invoice_test', 'AdminController@pdfTest');
