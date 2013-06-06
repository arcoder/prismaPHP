<?php
/**
 * User: albertoruffo
 */

class Mail {

    private static $mailer = null;

    public static function setTransport() {
        $transport = Swift_SmtpTransport::newInstance(Config::SMTP_HOST, Config::SMTP_PORT)
            ->setUsername(Config::SMTP_USERNAME)
            ->setPassword(Config::SMTP_PASSWORD)
        ;
        self::$mailer = Swift_Mailer::newInstance($transport);
    }



    public static function contact(array $data) {
        $message = Swift_Message::newInstance()


            // Give the message a subject
            ->setSubject('Contacts - '.Config::APP)
            ->setFrom(array($data['from']['email']));
        $message->setTo(array($data['to']['email'] => $data['to']['label']));
        $message->setBody($data['message']);
        return self::$mailer->send($message);
    }
}