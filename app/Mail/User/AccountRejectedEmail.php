<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountRejectedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $rejectionReason = null)
    {
        $this->user = $user;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Account Registration Rejected - ArtRights Platform')
                    ->view('blades.emails.account-rejected');
    }
}

