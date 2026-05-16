<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PersonCapturedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Person
     */
    public $person;

    /**
     * Create a new message instance.
     *
     * @param  Person  $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You have been captured on the system')
            ->with(['person' => $this->person])
            ->view('emails.person-captured');
    }
}
