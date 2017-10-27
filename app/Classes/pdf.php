<?php
namespace App\Classes;

use App\Bookings;
use App\booked_items;
use App\custom_items;
use App\Admin;
use App\Classes\Common;

class pdf
{
    public static function createInvoice($id, $demo = false)
    {
        $site = Request()->get('_site');

        if (!$demo) {
            $booking = Bookings::findOrFail($id);
            $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
              ->where('booked_items.bookingID', '=', $id)
              ->where('booked_items.number', '!=', '0')
              ->join('catalog', 'booked_items.item', '=', 'catalog.id')
              ->get();

            $customItems = custom_items::select('description', 'number', 'price')
              ->where('booking', $id)
              ->where('number', '!=', '0')
              ->get();
        } else {
            $booking = new Bookings;
            $booking->id = 12;
            $booking->name = "Demo Invoice";
            $booking->email = "demo@example.com";
            $booking->isDurham = 0;
            $booking->start = '2017-08-10 12:00:00';
            $booking->end = '2017-08-11 12:00:00';
            $booking->status = 4;
            $booking->days = 1;
            $booking->internal = 0;
            $booking->template = 0;
            $booking->vat = 1;

            $bookedItems = array(
              (object)['description' => 'Test item 1', 'number' => 2, 'dayPrice' => 8.5, 'weekPrice' => '17']
            );

            $customItems = [];
        }

        $hiresID = $site->hiresManager;
        $hiresManager = Admin::findOrFail(intval($hiresID));
        $hiresEmail = $site->hiresEmail;

        Common::calcAllCosts($booking, $bookedItems, $customItems);

        $name = 'invoice_' . $id . '.pdf';

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView(
            'pdf.invoice', [
            'booking' => $booking,
            'site' => $site,
            'items' => $bookedItems,
            'custom' => $customItems,
            'manager' => $hiresManager,
            'hiresEmail' => $hiresEmail
            ]
        );
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(5, 3, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 8, array(0, 0, 0));

        if (!$demo) {
            $pdf->save(base_path() . '/storage/invoices/' . $name);
            Bookings::findOrFail($id)->update(['invoice' => $name]);
        } else {
            return $pdf->stream();
        }
    }
}
