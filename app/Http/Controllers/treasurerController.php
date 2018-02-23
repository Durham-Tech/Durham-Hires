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
        $site = Request()->get('_site');
        $bookings = Bookings::orderBy('start')
              ->where('site', $site->id)
              ->where('status', '=', 4)
              ->where('vat', '=', 1)
              ->get();

        return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => 0, 'bookings' => $bookings]);
    }

    public function submit(Treasurer $request)
    {
        $site = Request()->get('_site');
        $prefix = strtolower(str_replace(' ', '', $site->invoicePrefix));
        $pregSuc = preg_match('/(?:' . preg_quote($prefix, '/') . ')?([0-9]+)/', strtolower(str_replace(' ', '', $request->ref)), $temp);
        if ($pregSuc) {
            $id = intval($temp[1]);
        } else {
            $id = -1;
        }
        $success = 2;
        try {
            $booking = Bookings::where('invoiceNum', $id)
                              ->where('site', $site->id)
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

        $attempt = intval($request->attempt);
        if ($success == 1) {
            return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => $success, 'bookings' => $vatBookings]);
        } else {
            if ($attempt == 2) {
                \Mail::send(new bankIncorrect($request->ref, $request->amount));
            }
            return view('bank.index')->with(['ref' => $request->ref, 'amount' => $request->amount, 'attempt' => $attempt + 1, 'success' => $success, 'bookings' => $vatBookings]);
        }
    }

    public function vatSorted($site, Bookings $booking)
    {
        $site = Request()->get('_site');
        if ($booking->status == 4 && $booking->site == $site->id) {
            $booking->status = 5;
            $booking->save();
        }
        return redirect('/' . $site . '/treasurer');
    }
}
