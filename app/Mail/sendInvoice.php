<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Bookings;

class sendInvoice extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $invoice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        //
        $this->id = $id;
        $this->invoice = Bookings::findOrFail($id)->invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tech hire invoice')
                    ->attach(base_path() . '/storage/invoices/' . $this->invoice)
                    ->markdown('emails.sendInvoice');
    }
}
