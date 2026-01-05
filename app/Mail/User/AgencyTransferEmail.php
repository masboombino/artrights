<?php

namespace App\Mail\User;

use App\Models\User;
use App\Models\Agency;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgencyTransferEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newAgency;
    public $oldAgencyName;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Agency $newAgency, $oldAgencyName = null)
    {
        $this->user = $user;
        $this->newAgency = $newAgency;
        $this->oldAgencyName = $oldAgencyName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Agency Transfer Notification - ArtRights Platform')
                    ->view('blades.emails.agency-transfer');
    }
}

