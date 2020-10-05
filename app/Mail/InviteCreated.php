<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Invite;

class InviteCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;

    /**
     * InviteCreated constructor.
     * @param Invite $invite
     */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@vitaleassets.nl')->subject('Uitnodiging deelname Vitale Assets')->markdown('emails.invite');
    }
}
