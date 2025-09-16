<?php

namespace App\Models\Entities;

class MailEntity
{

    public $subject = '';
    public $message = '';
    public $sendTo = '017@i-marketing.kz';
    public $sendFrom;

    public function __construct()
    {
        $this->sendFrom = 'info@'. request()->server("HTTP_HOST");
    }
}
