<?php

namespace App\Services;

use App\Mail\SubmissionReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class SubmissionNotifier
{
    /**
     * Queue the owner's email for an already saved submission. A failure here
     * is reported, not thrown: the visitor's submission has already succeeded.
     */
    public function notify(ContactMessage $submission): void
    {
        $destination = config('mail.owner_address');

        if (blank($destination)) {
            return;
        }

        rescue(fn () => Mail::to($destination)->queue(new SubmissionReceived($submission)));
    }
}
