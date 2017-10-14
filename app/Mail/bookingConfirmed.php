<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;
use App\Bookings;
use App\Site;

class bookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $hiresEmail;
    public $site;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        //
        $this->id = $id;
        $this->booking = Bookings::findOrFail($id);
        $this->site = Site::findOrFail($this->booking->site);

        $this->hiresEmail = $site->hiresEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo(Common::hiresEmail())
            ->subject('Trevs Tech booking conformation')
            ->markdown('emails.bookingConfirmed');
    }
}
