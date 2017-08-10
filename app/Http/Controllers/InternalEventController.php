<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;
use App\Bookings;
use App\booked_items;
use App\Classes\Items;
use App\Classes\CAuth;
use App\Http\Requests\NewInternal;

class InternalEventController extends Controller
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

        $data = Bookings::where('site', $site->id)
            ->whereRaw('end > NOW()')
            ->where('internal', '1')
            ->orderBy('start')
            ->get();

        return View::make('bookings.internal.index')
            ->with(['data' => $data, 'site' => $site]);
    }


    public function create()
    {
        $site = Request()->get('_site');
        $templates = Bookings::where('site', $site->id)
            ->where('template', '1')
            ->get();
        return View::make('bookings.internal.edit')
            ->with(['templates' => $templates, 'site' => $site]);
    }

    public function store(NewInternal $request)
    {
        $site = Request()->get('_site');

        $booking = new Bookings;
        $booking->name = $request->name;
        $start = strtotime($request->start)+43200;
        $end = strtotime($request->end)+43200;
        $booking->start = date("Y-m-d H:i:s", $start);
        $booking->end = date("Y-m-d H:i:s", $end);
        $booking->days = ($end - $start)/(86400);

        $booking->status = 2;
        $booking->internal = 1;
        $booking->isDurham = 0;
        $booking->email = CAuth::user()->email;
        $booking->user = '';
        $booking->site = $site->id;

        $booking->save();

        if ($request->template != 0) {

            $template = booked_items::where('bookingID', $request->template)
                  ->get();
            foreach($template as $x){
                booked_items::updateOrCreate(
                    ['bookingID' => $booking->id, 'item' => $x->item],
                    ['number' => $x->number]
                );
            }
            $items = new Items;
            $errorList = $items->changeTime($booking->id, $booking->start, $booking->end, true);
            $items->correctDuplicateBookings($booking);

            return redirect('/' . $site->slug . '/internal/' . $booking->id)
                ->with('unavalible', $errorList->name)
                ->with('uQuant', $errorList->number);
        } else {
            return redirect('/' . $site->slug . '/internal/' . $booking->id);
        }

    }

    public function show($s, $id)
    {
        $site = Request()->get('_site');
        $booking = Bookings::findOrFail($id);
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

            return View::make('bookings.internal.view')
                          ->with(
                              [
                              'booking' => $booking,
                              'items' => $bookedItems,
                              'site' => $site
                              ]
                          );

    }

    public function destroy($site, $id)
    {
        $booking = Bookings::findOrFail($id);
        if ($booking->internal == '1') {
            $booking->delete();
        }
        return redirect('/' . $site . '/internal');
    }

    public function addItems($id)
    {
        $site = Request()->get('_site');
        $items = new Items;
        $booking = Bookings::find($id);
        $data = $items->getAvalible($booking);

        if (($booking->email == CAuth::user()->email && $booking->status < 2) || CAuth::checkAdmin()) {
            return View::make('items.index')
                          ->with(['data'=>$data, 'edit'=>true, 'booking'=>$booking, 'site' => $site]);
        } else {
            return redirect()->route('items.index', $site->slug);
        }
    }

    public function updateItems(Request $request, $id)
    {
        $site = Request()->get('_site');
        $items = new Items;
        $booking = Bookings::find($id);
        $data = $items->getAvalibleArray($booking);

        if (($booking->email == CAuth::user()->email && $booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3)) {
            $inputs = $request->input();
            unset($inputs['_token']);

            $bookedItems = booked_items::where('bookingID', $id)
              ->get()
              ->keyBy('item')
              ->toArray();
            foreach ($inputs as $item => $quantity) {
                if (isset($bookedItems[$item]) || $quantity != 0) {
                    $quantity = (int)$quantity;
                    if (is_int($item) && is_int($quantity)) {
                        if ($quantity <= $data[$item]->available) {
                            booked_items::updateOrCreate(
                                ['bookingID' => $id, 'item' => $item],
                                ['number' => $quantity]
                            );
                        }
                    }
                }
            }

            if ($booking->status >= 2) {
                $booking->save();
                $items->correctDuplicateBookings($booking);
            }

            return redirect()->route('bookings.show', ['id' => $id, 'site' => $site->slug]);
        } else {
            return redirect()->route('items.index', $site->slug);
        }
    }

}
