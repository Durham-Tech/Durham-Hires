<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Treasurer;
use App\Bookings;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\bankIncorrect;

class treasurerController extends Controller
{
    public function __construct()
    {
        $this->middleware('login', ['except' => ['index', 'show']]);
        $this->middleware('treasurer');
    }

    public function index()
    {
        return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => 0]);
    }

    public function submit(Treasurer $request)
    {
        $id = $request->ref;
        $success = 2;
        try {
            $booking = Bookings::where('id', $id)
                              ->where('status', 3)
                              ->firstOrFail();

            if ($request->amount == round($booking->totalPrice, 2)) {
                $booking->status = 4;
                $booking->save();
                $success = 1;
            }
        } catch (ModelNotFoundException $e) {
            $success = 3;
        }

        if ($success == 1) {
            return view('bank.index')->with(['ref' => '', 'amount' => '', 'attempt' => 1, 'success' => $success]);
        } else {
            if ($request->attempt == 2) {
                \Mail::send(new bankIncorrect($request->ref, $request->amount));
            }
            return view('bank.index')->with(['ref' => $request->ref, 'amount' => $request->amount, 'attempt' => $request->attempt + 1, 'success' => $success]);
        }
    }
}
