<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Bookings;
use App\booked_items;
use App\Classes\Items;
use App\Http\Requests\NewTemplate;
use App\Classes\CAuth;

class TemplateController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }

    public function index()
    {
        $templates = Bookings::where('template', '1')
          ->get();
        return view('bookings.templates.index')->with(['templates'=>$templates]);
    }


    public function create()
    {
        return View::make('bookings.templates.edit');
    }

    public function store(NewTemplate $request)
    {
        $booking = new Bookings;
        $booking->name = $request->name;
        $booking->start = date("Y-m-d H:i:s", 0);
        $booking->end = date("Y-m-d H:i:s", 0);
        $booking->days = $request->days;

        $booking->email = '';
        $booking->user = '';
        $booking->status = 0;

        $booking->template = 1;

        $booking->save();
        return redirect('/templates/' . $booking->id);
    }

    public function show($id)
    {
        $template = Bookings::findOrFail($id);
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();
        return View::make('bookings.templates.view')
                      ->with(
                          [
                          'template' => $template,
                          'items' => $bookedItems,
                          ]
                      );
    }

    public function edit($id)
    {
        $old = Bookings::findOrFail($id);

        return View::make('bookings.templates.edit')
                      ->with(['old' => $old]);

    }

    public function update(NewTemplate $request, $id)
    {
        $booking = Bookings::findOrFail($id);
        $booking->name = $request->name;
        $booking->days = $request->days;

        $booking->save();

        return redirect('/templates/' . $booking->id);
    }

    public function destroy($id)
    {
        $booking = Bookings::findOrFail($id);
        if ($booking->template == '1') {
            $booking->delete();
        }
        return redirect('/templates');
    }

}
