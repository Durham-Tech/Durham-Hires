<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Classes\Common;
use App\Bookings;
use App\Site;
use App\Admin;

class itemRemoved extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $errorList;
    public $hiresManager;
    public $site;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $errorList)
    {
        //
        $this->id = $id;
        $this->booking = Bookings::findOrFail($id);
        $this->site = Request()->get('_site');
        $this->errorList = $errorList;
        $this->hiresManager = Admin::findOrFail($this->site->hiresManager)->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Items Unavailable - Tech Hire')
            ->replyTo(Common::hiresEmail())
            ->markdown('emails.itemRemoved');
    }
}
