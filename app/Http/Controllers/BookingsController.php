<?php

namespace App\Http\Controllers;

use DB;
use App\Bookings;
use App\booked_items;
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
        $this->middleware('admin', ['only' => ['edit', 'update', 'destroy', 'changeState']]);

        $this->status = ['Unconfirmed', 'Submitted', 'Confirmed', 'Returned', 'Paid'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        if (CAuth::checkAdmin()){
          $data = Bookings::orderBy('id', 'DESC')->get();
        } else {
          $data = Bookings::orderBy('id', 'DESC')
                ->where('email', '=', CAuth::user()->email)
                ->get();
        }

        return View::make('bookings.index')
            ->with(['data' => $data, 'statusArray' => $this->status]);
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
        $start = strtotime($request->start)+43200;
        $end = strtotime($request->end)+43200;
        $booking->start = date("Y-m-d H:i:s", $start);
        $booking->end = date("Y-m-d H:i:s", $end);
        $days = ($end - $start)/(86400);
        $rem = $days % 7;
        echo $days;
        echo $rem;
        if ($rem > 2) {
          $booking->twoDays = 0;
          $booking->weeks = floor($days/7) + 1;
        } else {
          $booking->twoDays = 1;
          $booking->weeks = floor($days/7);
        }

        if (CAuth::checkAdmin(4)){
          $booking->status = 1;
          $this->validate($request, [
              'email' => 'required|email'
          ]);
          $details = Common::getDetailsEmail($request->email);
            if ($details){
              $booking->email = $request->email;
              $booking->user = $details->surname;
              $booking->user = ucwords(strtolower(explode(',', $details->firstnames)[0] . ' ' . $details->surname));
            }
        } else {
          $booking->status = 0;
          $temp = CAuth::user();
          $booking->email = $temp->email;
          $booking->user = ucwords(strtolower(explode(',', $temp->firstnames)[0] . ' ' . $temp->surname));
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
        $bookedItems = DB::table('booked_items')
            ->select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        $booking->status_string = $this->status[$booking->status];

        if ($booking->email == CAuth::user()->email || CAuth::checkAdmin()){
          return View::make('bookings.view')->with(['booking' => $booking, 'items' => $bookedItems]);
        } else {
          return redirect()->route('items.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $old = Bookings::findOrFail($id);

        return View::make('bookings.edit')->with(['old' => $old]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewBooking $request, Bookings $booking)
    {
      $this->validate($request, [
          'email' => 'required|email'
      ]);
      $booking->name = $request->name;
      // Need to check for colitions before changing dates!
      // $booking->start = $request->start;
      // $booking->end = $request->end;
      $booking->status = 0;
      $details = Common::getDetailsEmail($request->email);
      if ($details){
        $booking->email = $request->email;
        $booking->user = $details->surname;
        $booking->user = ucwords(strtolower(explode(',', $details->firstnames)[0] . ' ' . $details->surname));
      }
      $booking->save();

       return redirect('/bookings/' . $booking->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookings $booking)
    {
       $booking->delete();
       return redirect('/bookings');
    }

    public function addItems($id){
      $items = new Items;
      $booking = Bookings::find($id);
      $data = $items->getAvalible($booking);

      if ($booking->email == CAuth::user()->email || CAuth::checkAdmin()){
        return View::make('items.index')->with(['data'=>$data, 'edit'=>TRUE, 'booking'=>$booking]);
      } else {
        return redirect()->route('items.index');
      }
    }

    public function updateItems(Request $request, $id){
      $items = new Items;
      $booking = Bookings::find($id);
      $data = $items->getAvalibleArray($booking);

      if ($booking->email == CAuth::user()->email || CAuth::checkAdmin()){
        $inputs = $request->input();
        unset($inputs['_token']);

        $bookedItems = booked_items::where('bookingID', $id)
              ->get()
              ->keyBy('item')
              ->toArray();
        foreach ($inputs as $item => $quantity){
          if (isset($bookedItems[$item]) || $quantity != 0){
          $quantity = (int)$quantity;
            if (is_int($item) && is_int($quantity)){
              if ($quantity <= $data[$item]->available){
                booked_items::updateOrCreate(
                    ['bookingID' => $id, 'item' => $item],
                    ['number' => $quantity]
                );
              }
            }
          }
        }

        $booking->status = 0;
        $booking->save();

        return redirect()->route('bookings.show', ['id' => $id]);
      } else {
        return redirect()->route('items.index');
      }

    }

    public function changeState(Request $request){
      $booking = Bookings::findOrFail($request->id);
      $booking->status = $request->status;
      $booking->save();
      return redirect()->route('bookings.index');
    }
}
