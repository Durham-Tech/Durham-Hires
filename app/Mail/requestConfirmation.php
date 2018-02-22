<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Classes\Common;
use App\Classes\CAuth;

class requestConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
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
            ->replyTo(CAuth::user()->email)
            ->subject('New hire request')
            ->markdown('emails.requestConfirmation');
    }
}
