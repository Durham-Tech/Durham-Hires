<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Bookings;
use App\Admin;
use App\Classes\Common;

class sendInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $hiresManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        //
        $site = Request()->get('_site');
        $this->id = $id;
        $this->booking = Bookings::findOrFail($id);
        $this->hiresManager = Admin::findOrFail($this->site->hiresManager)->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->site->name . ' hire invoice')
            ->replyTo(Common::hiresEmail())
            ->attach(base_path() . '/storage/invoices/' . $this->booking->invoice)
            ->markdown('emails.sendInvoice');
    }
}
