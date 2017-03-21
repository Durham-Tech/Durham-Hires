<?php

namespace App\Http\Controllers;

use App\Bookings;
use Illuminate\Http\Request;
use View;
use App\Models\Items;
use App\Classes\CAuth;
use App\Http\Requests\NewBooking;
use App\Classes\Common;

class BookingsController extends Controller
{

    public function __construct() {
        $this->middleware('login');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Bookings::orderBy('id', 'DESC')->get();
        $status = [];

        foreach ($data as $booking) {
          switch ($booking->status) {
            case 0:
              $status[$booking->id] = 'Unconfirmed';
              break;

            case 1:
              $status[$booking->id] = 'Confirmed';
              break;

            default:
              $status[$booking->id] = 'Unconfirmed';
              break;
          }
        }
        return View::make('bookings.index')
            ->with(['data' => $data, 'status' => $status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('bookings.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewBooking $request)
    {
        $booking = new Bookings;
        $booking->name = $request->name;
        $booking->start = $request->start;
        $booking->end = $request->end;
        $booking->status = 0;
        if (CAuth::checkAdmin(4)){
          $this->validate($request, [
              'email' => 'required|email'
          ]);
            if (Common::getDetailsEmail($request->email)){
              $booking->email = $request->email;
            }
        } else {
          $booking->email = CAuth::user()->email;
        }
        $booking->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Bookings::findOrFail($id);

        switch ($booking->status) {
          case 0:
            $booking->status_string = 'Unconfirmed';
            break;

          case 1:
            $booking->status_string = 'Confirmed';
            break;

          default:
            $booking->status_string = 'Unconfirmed';
            break;
        }

        return View::make('bookings.view')->with(['booking' => $booking]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addItems($id){
      $items = new Items;
      $data = $items->getAvalible($id);

      return View::make('items.index')->with(['data'=>$data, 'edit'=>TRUE]);
    }
}
