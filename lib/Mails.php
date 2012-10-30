<?php

class Mails
{
    public static function welcome()
    {
        $mailer =  Swift_Mailer::newInstance(Mail::getTransport());
        $message = Swift_Message::newInstance('PHP file')
          ->setFrom(array('john@doe.com' => 'John Doe'))
          ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
          ->setBody('This is a long message, you should read because you sent it from PHP script')
          ;

        //Send the message
        $result = $mailer->send($message);
    }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
