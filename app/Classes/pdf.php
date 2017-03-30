<?php
namespace App\Classes;

use App\Bookings;
use App\booked_items;
use App\Classes\Common;
use App

class pdf{

    public static function createInvoice($id){
        $booking = Bookings::findOrFail($id);
        $bookedItems = booked_items::select('description', 'number', 'dayPrice', 'weekPrice')
            ->where('booked_items.bookingID', '=', $id)
            ->where('booked_items.number', '!=', '0')
            ->join('catalog', 'booked_items.item', '=', 'catalog.id')
            ->get();

        // $booking->total = 0;
        // $days = $booking->days - $booking->discDays;
        // if ($days < 0){
        //   $days = 0;
        // }
        // foreach ($bookedItems as $item){
        //   $item->unitCost = Common::calcItemCost($days, $item->dayPrice, $item->weekPrice);
        //   $item->cost = $item->unitCost * $item->number;
        //   $booking->total += $item->cost;
        // }

        Common::calcAllCosts($booking, $bookedItems);

        $name = 'invoice_' . $id . '.pdf';

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.invoice', ['booking' => $booking, 'items' => $bookedItems]);
        $pdf->save(base_path() . '/storage/invoices/' . $name);

        Bookings::findOrFail($id)->update(['invoice' => $name]);
    }
}
