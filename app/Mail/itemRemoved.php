<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;
use App\Bookings;
use App\Settings;

class itemRemoved extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $booking;
    public $errorList;
    public $hiresEmail;

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
        $this->errorList = $errorList;
        $this->hiresEmail = Settings::where('name', 'hiresEmail')->firstOrFail()->value;
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
