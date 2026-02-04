<?php

namespace App\Mailer;

use App\Config\ConfigInterface;
use App\Mailer\MailerInterface;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer implements MailerInterface
{
    private PHPMailer $mailer;
    public function __construct(
        private ConfigInterface $config
    ) {
        $this->setMailer();
    }

    private function setMailer(): void
    {
        $this->mailer = new PHPMailer(true);
    }

    public function sendMail(array $data, string $mailTo = 'nel.work.111@gmail.com'): bool
    {
        try {
            $this->setConfigMail($mailTo);

            $this->mailer->Body = implode(' <br>', $data);

            $this->mailer->send();

            return true;

        } catch (\Exception $ex) {
            return "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
        }

    }

    private function registerCompanyBody(){
        $html = "<div>TEST</div>";
        $this->mailer->Body = $html;
    }

    private function setConfigMail(string $mailTo): void
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config->get('smtp.host');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config->get('smtp.username');
        $this->mailer->Password = $this->config->get('smtp.password');
        $this->mailer->Port = $this->config->get('smtp.port');
        $this->mailer->CharSet = $this->mailer::CHARSET_UTF8;
        $this->mailer->setFrom($this->config->get('smtp.username'), 'Pegas.CRM');
//        $mail->Subject =  '=?UTF-8?B?'.base64_encode($subject_name.''.$next_id).'?=';
        $this->mailer->Subject = 'Оповещение в PEGAS.CRM';

        $this->mailer->addAddress($mailTo, 'Вам');
        $this->mailer->isHTML(true);

    }
}