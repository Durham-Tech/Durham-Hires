<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Classes\Common;

class itemRemoved extends Mailable
{
    use Queueable, SerializesModels;
    public $id;
    public $errorList;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $errorList)
    {
        //
        $this->id = $id;
        $this->errorList = $errorList;
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
