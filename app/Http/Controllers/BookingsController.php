<?php

namespace App\Http\Controllers;

use App\Bookings;
use App\booked_items;
use Illuminate\Http\Request;
use View;
use App\Models\Items;
use App\Classes\CAuth;
use App\Http\Requests\NewBooking;
use App\Classes\Common;
use App\Classes\pdf;
use App\Mail\requestConfirmation;
use App\Mail\bookingConfirmed;

class BookingsController extends Controller
{

    public function __construct() {
        $this->middleware('login');
        $this->middleware('admin', ['only' => ['edit', 'update']]);

        $this->status = ['Unconfirmed', 'Submitted', 'Confirmed', 'Returned', 'Paid'];
        $this->nextStatus = ['Confirm Booking', 'Booking Returned', 'Booking Paid'];
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
          $data = Bookings::orderBy('start')->get()
                ->where('status', '!=', 4);
        } else {
          $data = Bookings::orderBy('start', 'DESC')
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
        $booking->days = ($end - $start)/(86400);

        if (CAuth::checkAdmin(4)){
          $booking->status = 2;
          $this->validate($request, [
              'email' => 'required|email'
          ]);
          $details = Common::getDetailsEmail($request->email);
            if ($details){
              $booking->email = $request->email;
              $booking->user = $details->surname;
              $booking->user = $details->name;
              \Mail::to($booking->email)->send(new bookingConfirmed);
            }

        } else {
          $booking->status = 0;
          $temp = CAuth::user();
          $booking->email = $temp->email;
          $booking->user = ucwords(strtolower(explode(',', $temp->firstnames)[0] . ' ' . $temp->surname));
        }
        $booking->save();
        return redirect('/bookings/' . $booking->id);
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
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        Common::calcAllCosts($booking, $bookedItems);

        $booking->status_string = $this->status[$booking->status];

        if ($booking->email == CAuth::user()->email || CAuth::checkAdmin()){
          return View::make('bookings.view')->with(['booking' => $booking, 'items' => $bookedItems, 'next' => $this->nextStatus]);
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
        return View::make('bookings.edit')->with(['old' => $old, 'statusArray' => $this->status]);
    }

    private function manageStatusChange(&$booking, $status){
      switch ($status){
        case 2:
          if ($booking->status <= 1){
            \Mail::to($booking->email)->send(new bookingConfirmed);
          }
          break;
        case 3:
          if ($booking->status != 3){
            pdf::createInvoice($booking->id);
            //Send invoice
          }
          break;
        case 4:
          if ($booking->status != 4){
            //Send thankyou
          }
          break;
        default:
          break;
      }

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

      $this->manageStatusChange($booking, $request->status);
      $booking->status = $request->status;

      if ($booking->email != $request->email){
        $details = Common::getDetailsEmail($request->email);
        if ($details){
          $booking->email = $request->email;
          $booking->user = $details->name;
        }
      }

      $booking->discDays = $request->discDays;
      $booking->discType = $request->discType;
      $booking->discValue = $request->discValue;
      $booking->fineDesc = $request->fineDesc;
      $booking->fineValue = $request->fineValue;

      $booking->save();

      return redirect('/bookings/' . $booking->id);
    }

    public function updateStatus(Request $request, Bookings $booking)
    {
      if (CAuth::checkAdmin(4)){

        $this->manageStatusChange($booking, $request->status);
        $booking->status = $request->status;

      } elseif (CAuth::user()->email == $booking->email){
        switch ($booking->status){
          case 0:
            $booking->status = 1;
            \Mail::to('test@example.com')->send(new requestConfirmation($booking->id));
            break;
          case 1:
            $booking->status = 0;
            break;
          default:
            break;
        }

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
      if (($booking->email == CAuth::user()->email || CAuth::checkAdmin()) && $booking->status < 2){
        $booking->delete();
      }
      return redirect('/bookings');
    }

    public function addItems($id){
      $items = new Items;
      $booking = Bookings::find($id);
      $data = $items->getAvalible($booking);

      if (($booking->email == CAuth::user()->email && $booking->status < 2) || CAuth::checkAdmin()){
        return View::make('items.index')->with(['data'=>$data, 'edit'=>TRUE, 'booking'=>$booking]);
      } else {
        return redirect()->route('items.index');
      }
    }

    public function updateItems(Request $request, $id){
      $items = new Items;
      $booking = Bookings::find($id);
      $data = $items->getAvalibleArray($booking);

      if (($booking->email == CAuth::user()->email && $booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3)){
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

        $booking->save();

        return redirect()->route('bookings.show', ['id' => $id]);
      } else {
        return redirect()->route('items.index');
      }
    }

}
