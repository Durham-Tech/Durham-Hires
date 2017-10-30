<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;
use App\Site;
use App\Admin;
use App\Bookings;

class paymentReceived extends Mailable
{
    use Queueable, SerializesModels;
    public $hiresEmail;
    public $name;
    public $site;
    public $hiresManager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->site = Request()->get('_site');
        $this->hiresEmail = $this->site->hiresEmail;
        $this->name = $name;
        $this->hiresManager = Admin::findOrFail($this->site->hiresManager)->name;
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
