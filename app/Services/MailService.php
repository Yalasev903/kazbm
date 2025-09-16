<?php

namespace App\Services;

use App\Models\Entities\MailEntity;
use Illuminate\Support\Facades\Log;

class MailService
{

    public function send(MailEntity $mailEntity)
    {

        $sendFrom = $mailEntity->sendFrom;

        $headers = "From: " . strip_tags($sendFrom) . "\r\n";
        $headers .= "Reply-To: " . strip_tags($sendFrom) . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html;charset=utf-8 \r\n";

        $isSent = mail($mailEntity->sendTo, $mailEntity->subject, $mailEntity->message, $headers);

        if (!$isSent) {
            Log::error("[App/Services] Failed to send email to: " . $mailEntity->sendTo);
        }
    }
}
