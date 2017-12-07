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
    public $site;
    public $hiresManager;
    public $updated;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $updated)
    {
        //
        $this->site = Request()->get('_site');
        $this->id = $id;
        $this->booking = Bookings::findOrFail($id);
        $this->hiresManager = Admin::findOrFail($this->site->hiresManager)->name;
        $this->updated = $updated;
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
            ->attach(
                base_path() . '/storage/invoices/' . $this->booking->invoice,
                ['as' => 'invoice_' . $this->booking->invoiceNum . '.pdf']
            )
            ->markdown('emails.sendInvoice');
    }
}
