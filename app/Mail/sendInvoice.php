<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Bookings;
use App\Classes\Common;
use App\Settings;

class sendInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $hiresEmail;

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
        $this->hiresEmail = Settings::where('name', 'hiresEmail')
            ->where('site', $site->id)
            ->firstOrFail()->value;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tech hire invoice')
            ->replyTo(Common::hiresEmail())
            ->attach(base_path() . '/storage/invoices/' . $this->booking->invoice)
            ->markdown('emails.sendInvoice');
    }
}
