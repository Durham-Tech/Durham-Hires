<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Classes\Common;
use App\Bookings;
use App\Admin;
use App\Site;

class bookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $hiresManager;
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
        $this->site = Request()->get('_site');
        $this->hiresManager = Admin::findOrFail($this->site->hiresManager)->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo(Common::hiresEmail())
            ->subject('Tech hire booking conformation')
            ->markdown('emails.bookingConfirmed');
    }
}
