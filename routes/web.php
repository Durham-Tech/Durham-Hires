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

Route::resource('categories', 'CategoryController');
Route::resource('items', 'ItemController');
Route::resource('admin', 'AdminController');
Route::resource('bookings', 'BookingsController');
Route::resource('templates', 'TemplateController');
Route::resource('internal', 'InternalEventController');

Route::get('bookings/index/complete', 'BookingsController@indexComplete')
          ->name('bookings.complete');
Route::get('bookings/{id}/invoice', 'BookingsController@getInvoice')
          ->name('bookings.invoice');
Route::get('bookings/{id}/add', 'BookingsController@addItems')
          ->name('bookings.add');
Route::post('bookings/{id}/add', 'BookingsController@updateItems');
Route::post('bookings/changestate', 'BookingsController@changeState');
Route::get('bookings/{id}/submit', 'BookingsController@submitBooking')
          ->name('bookings.submit');
Route::patch('bookings/{booking}/updateStatus', 'BookingsController@updateStatus')
          ->name('bookings.updateStatus');

Route::post('admin/save', 'AdminController@Save')
          ->name('admin.save');

Route::get('treasurer', 'treasurerController@index')
          ->name('bank.index');
Route::post('treasurer', 'treasurerController@submit')
          ->name('bank.submit');

Route::get('/', 'publicController@index');

Route::get(
    '/login', ['as'=>'login',function () {
        return view('login');
    }]
);

Route::post('/login', 'customAuth@checkAuth');
Route::post('/logout', 'customAuth@logout');


// Calender routes
Route::get('/calendar23', 'CalendarController@downloadCalendar');
