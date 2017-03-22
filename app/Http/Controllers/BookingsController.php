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
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function getStatus($int){
      switch ($int) {
        case 0:
          return 'Unconfirmed';
          break;

        case 1:
          return 'Confirmed';
          break;

        default:
          return 'Unconfirmed';
          break;
      }
    }

    public function index()
    {
        //
        $data = Bookings::orderBy('id', 'DESC')->get();
        $status = [];

        foreach ($data as $booking) {
          $booking->status_string = $this->getStatus($booking->status);
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
        $bookedItems = DB::table('booked_items')
            ->select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        $booking->status_string = $this->getStatus($booking->status);

        return View::make('bookings.view')->with(['booking' => $booking, 'items' => $bookedItems]);
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
      $booking = Bookings::find($id);
      $data = $items->getAvalible($booking);

      return View::make('items.index')->with(['data'=>$data, 'edit'=>TRUE, 'booking'=>$booking]);
    }

    public function updateItems(Request $request, $id){
      $items = new Items;
      $booking = Bookings::find($id);
      $data = $items->getAvalibleArray($booking);

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
    }
}
