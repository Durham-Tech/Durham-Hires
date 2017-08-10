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
        $site = Request()->get('_site');

        $templates = Bookings::where('template', '1')
          ->where('site', $site->id)
          ->get();
        return view('bookings.templates.index')->with(['templates'=>$templates, 'site' => $site]);
    }


    public function create($s)
    {
        $site = Request()->get('_site');
        return View::make('bookings.templates.edit')->with(['site' => $site]);
    }

    public function store(NewTemplate $request)
    {
        $site = Request()->get('_site');
        $booking = new Bookings;
        $booking->name = $request->name;
        $booking->start = date("Y-m-d H:i:s", 0);
        $booking->end = date("Y-m-d H:i:s", 0);
        $booking->days = $request->days;

        $booking->email = '';
        $booking->user = '';
        $booking->isDurham = 0;
        $booking->status = 0;
        $booking->site = $site->id;

        $booking->template = 1;

        $booking->save();
        return redirect('/' . $site->slug . '/templates/' . $booking->id);
    }

    public function show($s, $id)
    {
        $site = Request()->get('_site');
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
                          'site' => $site
                          ]
                      );
    }

    public function edit($s, $id)
    {
        $site = Request()->get('_site');
        $old = Bookings::findOrFail($id);

        return View::make('bookings.templates.edit')
                      ->with(['old' => $old, 'site' => $site]);
    }

    public function update(NewTemplate $request, $s, $id)
    {
        $site = Request()->get('_site');
        $booking = Bookings::findOrFail($id);
        $booking->name = $request->name;
        $booking->days = $request->days;
        $booking->site = $site->id;

        $booking->save();

        return redirect('/' . $site->slug . '/templates/' . $booking->id);
    }

    public function destroy($site, $id)
    {
        $booking = Bookings::findOrFail($id);
        if ($booking->template == '1') {
            $booking->delete();
        }
        return redirect('/' . $site . '/templates');
    }

}
