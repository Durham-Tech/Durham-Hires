<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Classes\Common;

class bankIncorrect extends Mailable
{
    use Queueable, SerializesModels;
    public $ref;
    public $amount;
    public $site;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ref, $amount)
    {
        //
        $this->ref = $ref;
        $this->amount = $amount;
        $this->site = Request()->get('_site');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(Common::hiresEmail())
            ->from("no-reply@" . env('MAIL_FROM_DOMAIN', '@example.com'))
            ->subject('Hires Payment Error')
            ->markdown('emails.bankIncorrect');
    }
}
