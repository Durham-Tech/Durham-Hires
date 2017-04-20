<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;
use App\Settings;
use App\Bookings;

class paymentReceived extends Mailable
{
    use Queueable, SerializesModels;
    public $hiresEmail;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->hiresEmail = Settings::where('name', 'hiresEmail')->firstOrFail()->value;
        $this->name = $name;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo(Common::hiresEmail())
            ->subject('Thank you for your payment')
            ->markdown('emails.paymentReceived');
    }
}
