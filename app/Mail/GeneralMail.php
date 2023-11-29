<?php

namespace App\Mail;

use App\Models\SiteInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected array $attributes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
        $this->afterCommit();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->attributes['email'])
            ->subject($this->attributes['subject'] ?? '')
            ->view('emails.general-mail')
            ->with('attributes', $this->attributes);
    }
}
