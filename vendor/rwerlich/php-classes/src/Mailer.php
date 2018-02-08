<?php

namespace Werlich;

use Rain\Tpl;

class Mailer {

    const FROM_NAME = "Ecommerce Werlich";
    const USERNAME = "ecommercewerlich@gmail.com";
    const PASSWORD = "testewerlich";

    private $mail;

    public function __construct($toAddress, $toName, $subject, $tplName, $data = array()) {
        $config = array(
            "tpl_dir" => $_SERVER["DOCUMENT_ROOT"] . "/ecommerce/views/email/",
            "cache_dir" => $_SERVER["DOCUMENT_ROOT"] . "/ecommerce/views-cache/",
            "debug" => false
        );
        Tpl::configure($config);
        $tpl = new Tpl;
        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }
        $html = $tpl->draw($tplName, true);
        $this->mail = new \PHPMailer;
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Debugoutput = 'html';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = Mailer::USERNAME;
        $this->mail->Password = Mailer::PASSWORD;
        $this->mail->setFrom(Mailer::USERNAME, Mailer::FROM_NAME);
        $this->mail->addAddress($toAddress, $toName);
        $this->mail->Subject = utf8_decode($subject);
        $this->mail->msgHTML($html);
    }

    public function send(): bool {
        return $this->mail->send();
    }

}
