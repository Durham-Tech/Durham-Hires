<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;

class bankIncorrect extends Mailable
{
    use Queueable, SerializesModels;
    public $ref;
    public $amount;

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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(Common::hiresEmail())
            ->subject('Hires Payment Error')
            ->markdown('email.bankIncorrect');
    }
}
