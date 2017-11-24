<?php

namespace Brime\Core\Helpers;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $Config;

    public function __construct()
    {
        $this->Config = new Config();
    }

    public function setup()
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $this->Config->get('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = $this->Config->get('SMTP_NAME');
        $mail->Password = $this->Config->get('SMTP_PSWD');
        $mail->Port = $this->Config->get('SMTP_PORT');

        return $mail;
    }

}
