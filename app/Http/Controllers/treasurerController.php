<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Treasurer;
use App\Bookings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\bankIncorrect;
use App\Mail\paymentReceived;

class treasurerController extends Controller
{
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('treasurer');
    }

    public function index()
    {
        $bookings = Bookings::orderBy('start')
              ->where('status', '=', 4)
              ->where('vat', '=', 1)
              ->get();

        return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => 0, 'bookings' => $bookings]);
    }

    public function submit(Treasurer $request)
    {
        $pregSuc = preg_match('/^techhires[0-9]+$/', strtolower(str_replace(' ', '', $request->ref)), $temp);
        if ($pregSuc) {
            $id = intval(str_replace('techhires', '', $temp[0]));
        } else {
            $id = 0;
        }
        $success = 2;
        try {
            $booking = Bookings::where('id', $id)
                              ->where('status', 3)
                              ->firstOrFail();

            if ($request->amount == round($booking->totalPrice, 2)) {
                $booking->status = 4;
                $booking->save();
                \Mail::to($booking->email)->send(new paymentReceived($booking->name));
                $success = 1;
            }
        } catch (ModelNotFoundException $e) {
            $success = 3;
        }

        $vatBookings = Bookings::orderBy('start')
              ->where('status', '=', 4)
              ->where('vat', '=', 1)
              ->get();

        if ($success == 1) {
            return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => $success, 'bookings' => $vatBookings]);
        } else {
            if ($request->attempt == 2) {
                \Mail::send(new bankIncorrect($request->ref, $request->amount));
            }
            return view('bank.index')->with(['ref' => $request->ref, 'amount' => $request->amount, 'attempt' => $request->attempt + 1, 'success' => $success, 'bookings' => $vatBookings]);
        }
    }

    public function vatSorted(Bookings $booking)
    {
        if ($booking->status == 4) {
            $booking->status = 5;
            $booking->save();
        }
        return redirect('/treasurer');
    }
}
