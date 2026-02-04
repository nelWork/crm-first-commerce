<?php

namespace App\Mailer;

interface MailerInterface
{
    public function sendMail(array $data,  string $mailTo): bool;

}