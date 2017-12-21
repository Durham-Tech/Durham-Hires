<?php

namespace App\Http\Controllers;

use App\Bookings;
use App\booked_items;
use App\custom_items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;
use App\Classes\Items;
use App\Classes\CAuth;
use App\Http\Requests\NewBooking;
use App\Classes\Common;
use App\Classes\pdf;
use App\Mail\requestConfirmation;
use App\Mail\bookingConfirmed;
use App\Mail\sendInvoice;
use App\Mail\paymentReceived;

class BookingsController extends Controller
{

    // Booking status:
    // 0 Unconfirmed
    // 1 Submitted
    // 2 Confirmed
    // 3 Returned
    // 4 Paid
    // 5 Paid and VAT sorted

    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin', ['only' => ['edit', 'update', 'indexComplete']]);

        $this->status = ['Unconfirmed', 'Submitted', 'Confirmed', 'Returned', 'Paid', 'Paid'];
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
        $site = Request()->get('_site');
        if (CAuth::checkAdmin()) {
            $data = Bookings::orderBy('start')
                ->where('site', $site->id)
                ->where('internal', 0)
                ->where('template', 0)
                ->where('status', '<', 4)
                ->Where(
                    function ($query) {
                        $query->where('status', '>', 0)
                            ->orWhere('end', '>=', date('Y-m-d H:i:s'));
                    }
                )
                ->get();
        } else {
            $data = Bookings::orderBy('start', 'DESC')
                ->where('site', $site->id)
                ->where('email', '=', CAuth::user()->email)
                ->where('internal', 0)
                ->where('template', 0)
                ->get();
        }

        return View::make('bookings.index')
            ->with(['data' => $data, 'statusArray' => $this->status]);
    }

    public function indexComplete()
    {
        $site = Request()->get('_site');
        $data = Bookings::orderBy('start', 'DESC')
              ->where('site', $site->id)
              ->where('status', '>=', 4)
              ->get();

        return View::make('bookings.old')
            ->with(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Request()->get('_site');
        // Reject if bookings diabled
        if (!($site->flags & 1)) {
            return redirect()->route('home', ['site' => $site->slug]);
        }
        return View::make('bookings.edit')
                ->with(['statusArray' => [0 => $this->status[0], 2 => $this->status[2]], 'allowDateEdit' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewBooking $request)
    {
        $site = Request()->get('_site');

        // Reject if bookings diabled
        if (!($site->flags & 1)) {
            return redirect()->route('home', ['site' => $site->slug]);
        }

        $booking = new Bookings;
        $booking->name = $request->name;
        $start = strtotime($request->start)+43200;
        $end = strtotime($request->end)+43200;
        $booking->start = date("Y-m-d H:i:s", $start);
        $booking->end = date("Y-m-d H:i:s", $end);
        $booking->days = ($end - $start)/(86400);
        $booking->vat = $request->vat;
        $booking->site = $site->id;

        if (CAuth::checkAdmin(4)) {
            $booking->status = $request->status;
            $this->validate(
                $request, [
                'email' => 'required|email'
                ]
            );
            $booking->email = $request->email;
            $details = Common::getDetailsEmail($request->email);
            if ($details) {
                $booking->user = $details->name;
                $booking->isDurham = 1;
            } else {
                $booking->isDurham = 0;
            }
        } else {
            $booking->status = 0;
            $temp = CAuth::user();
            $booking->email = $temp->email;
            $booking->isDurham = 1;
            $booking->user = ucwords(strtolower(explode(',', $temp->firstnames)[0] . ' ' . $temp->surname));
        }
        $booking->save();
        if ($booking->status == 2 && CAuth::checkAdmin(4)) {
            \Mail::to($booking->email)->send(new bookingConfirmed($booking->id));
        }
        return redirect('/' . $site->slug . '/bookings/' . $booking->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($site, $id)
    {
        $site = Request()->get('_site');
        $booking = Bookings::where('site', $site->id)->findOrFail($id);
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        $customItems = custom_items::select('description', 'number', 'price')
            ->where('booking', $id)
            ->where('number', '!=', '0')
            ->get();

        Common::calcAllCosts($booking, $bookedItems, $customItems);

        // Correction for VAT marker
        if ($booking->status == 5) {
            $booking->status = 4;
        }

        $booking->status_string = $this->status[$booking->status];

        if ($booking->email == CAuth::user()->email || CAuth::checkAdmin()) {
            return View::make('bookings.view')
                          ->with(
                              [
                              'booking' => $booking,
                              'items' => $bookedItems,
                              'custom' => $customItems,
                              'next' => $this->nextStatus
                              ]
                          );
        } else {
            return redirect()->route('items.index', ['site' => $site->slug]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($site, $id)
    {
        $site = Request()->get('_site');
        $old = Bookings::where('site', $site->id)->findOrFail($id);

        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        // $start = date_create($old->start);
        // $old->start = date_format($start, "d/m/Y");
        //
        // $end = date_create($old->end);
        // $old->end = date_format($end, "d/m/Y");
        //
        $old->fineValue = number_format($old->fineValue, 2);
        if ($old->discType == 0) {
            $old->discValue = number_format($old->discValue, 2);
        }

        // Correction for VAT marker
        if ($old->status == 5) {
            $old->status = 4;
        }
        return View::make('bookings.edit')
                      ->with(['old' => $old, 'statusArray' => $this->status, 'allowDateEdit' => (count($bookedItems) == 0)]);
    }

    private function manageStatusChange(&$booking, $status)
    {
        switch ($status) {
        case 2:
            if ($booking->status <= 1) {
                $items = new Items;
                $items->correctDuplicateBookings($booking);
                \Mail::to($booking->email)->send(new bookingConfirmed($booking->id));
            }
            break;
        case 3:
            if ($booking->status != 3) {
                pdf::createInvoice($booking->id);
                \Mail::to($booking->email)->send(new sendInvoice($booking->id, false));
            }
            break;
        case 4:
            if ($booking->status != 4) {
                \Mail::to($booking->email)->send(new paymentReceived($booking->name));
            }
            break;
        default:
            break;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewBooking $request, $site, Bookings $booking)
    {
        $site = Request()->get('_site');
        if ($booking->site != $site->id) {
            abort(403);
        }
        $this->validate(
            $request, [
            'email' => 'required|email'
            ]
        );
        $booking->name = $request->name;
        $booking->user = $request->user;

        if ($request->status <= 3) {
            $booking->vat = $request->vat;
        }

        // Only allow date change if no items are in the order
        $itemCount = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $booking->id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->count();
        if ($itemCount == 0) {
            $start = strtotime($request->start)+43200;
            $end = strtotime($request->end)+43200;
            $booking->start = date("Y-m-d H:i:s", $start);
            $booking->end = date("Y-m-d H:i:s", $end);
            $booking->days = ($end - $start)/(86400);
        }


        if (!($booking->status == 5 && $request->status == 4)) {
            $this->manageStatusChange($booking, $request->status);
            $booking->status = $request->status;
        }

        if ($booking->email != $request->email) {
            $this->validate(
                $request, [
                'email' => 'required|email'
                ]
            );
            $booking->email = $request->email;
            $details = Common::getDetailsEmail($request->email);
            if ($details) {
                $booking->user = $details->name;
                $booking->isDurham = 1;
            } else {
                $booking->isDurham = 0;
            }
            // $details = Common::getDetailsEmail($request->email);
            // if ($details) {
            //     $booking->email = $request->email;
            //     $booking->user = $details->name;
            // }
        }

        $booking->discDays = $request->discDays;
        $booking->discType = $request->discType;
        $booking->discValue = $request->discValue;
        $booking->fineDesc = $request->fineDesc;
        $booking->fineValue = $request->fineValue;

        $booking->save();

        if ($booking->status == 3) {
            $oldCost = $booking->totalPrice;
            pdf::createInvoice($booking->id);
            $newCost = Bookings::find($booking->id)->totalPrice;
            if($oldCost != $newCost) {
                \Mail::to($booking->email)->send(new sendInvoice($booking->id, true));
            }
        }

        return redirect('/' . $site->slug . '/bookings/' . $booking->id);

    }

    public function updateStatus(Request $request, $site, Bookings $booking)
    {
        $site = Request()->get('_site');
        if (CAuth::checkAdmin(4)) {
            $this->manageStatusChange($booking, $request->status);
            $booking->status = $request->status;
        } elseif (CAuth::user()->email == $booking->email) {
            switch ($booking->status) {
            case 0:
                $booking->status = 1;
                \Mail::send(new requestConfirmation($booking->id));
                break;
            case 1:
                $booking->status = 0;
                break;
            default:
                break;
            }
        }
        $booking->save();
        return redirect('/' . $site->slug . '/bookings/' . $booking->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($site, Bookings $booking)
    {
        $site = Request()->get('_site');
        if (($booking->email == CAuth::user()->email && $booking->status < 2) || (CAuth::checkAdmin() && $booking->status < 3 && $booking->site == $site->id)) {
            $booking->delete();
        }
        return redirect('/' . $site->slug . '/bookings');
    }

    public function addItems($site, $id)
    {
        $site = Request()->get('_site');
        $items = new Items;
        $booking = Bookings::where('site', $site->id)->findOrFail($id);
        $data = $items->getAvalible($booking);
        $custom_items = custom_items::where('booking', $booking->id)->get();

        if (($booking->email == CAuth::user()->email && $booking->status < 2) || CAuth::checkAdmin()) {
            return View::make('items.index')
                          ->with(['data'=>$data, 'edit'=>true, 'booking'=>$booking, 'custom'=>$custom_items]);
        } else {
            return redirect()->route('items.index', ['site' => $site->slug]);
        }
    }

    public function updateItems(Request $request, $site, $id)
    {
        $site = Request()->get('_site');
        $items = new Items;
        $booking = Bookings::where('site', $site->id)->findOrFail($id);
        $data = $items->getAvalibleArray($booking);
        $custom_items = custom_items::where('booking', $booking->id)->get();

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

            if (!$booking->internal && !$booking->template) {
                foreach ($custom_items as $item){
                    $key = array_search($item->id, $request->id);
                    if ($key !== false) {
                        $item->description = $request->description[$key];
                        $item->number = $request->quantity[$key];
                        $item->price = $request->price[$key];
                        $item->save();
                    } else {
                        $item->delete();
                    }
                }
                $request->id = (array)$request->id; // Make sure array of item ids is actually an array instead of single value
                foreach ($request->id as $key => $cus_id){
                    if (is_null($cus_id)) {
                        if(!empty($request->description[$key]) && !empty($request->price[$key]) && !empty($request->quantity[$key])) {
                            $item = new custom_items;
                            $item->booking = $booking->id;
                            $item->description = $request->description[$key];
                            $item->number = $request->quantity[$key];
                            $item->price = $request->price[$key];
                            $item->save();
                        }
                    }
                }
            }

            if ($booking->status >= 2) {
                // $booking->save();
                $items->correctDuplicateBookings($booking);
            }

            if ($booking->template == '1') {
                return redirect()->route('templates.show', ['id' => $id, 'site' => $site->slug]);
            } elseif ($booking->internal == '1') {
                return redirect()->route('internal.show', ['id' => $id, 'site' => $site->slug]);
            } else {
                return redirect()->route('bookings.show', ['id' => $id, 'site' => $site->slug]);
            }
        } else {
            return redirect()->route('items.index', ['site' => $site->slug]);
        }
    }


    public function getInvoice($site, $id)
    {
        $site = Request()->get('_site');
        $booking = Bookings::where('site', $site->id)->findOrFail($id);
        if (($booking->email == CAuth::user()->email) || (CAuth::checkAdmin([1,4]))) {
            $invoice = $booking->invoice;
            if (!empty($invoice)) {
                $file = base_path() . '/storage/invoices/' . $invoice;
                return response()->file($file, ['Content-Disposition' => 'inline; filename="invoice_'.$booking->invoiceNum.'.pdf"']);
            }
        }
    }
}
