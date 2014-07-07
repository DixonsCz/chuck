<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MailService
 *
 * @author nano
 */
class MailService implements IMailService{
    
    /**
     * 
     * @param MsgContainer $mailer
     */
       
    public function send(MsgContainer $mailer){
        
        $this->mail = new Message;
        $this->mail->setFrom('Chuck Norris <no-reply@dixonsretail.com>')
        ->setSubject('[Release note] ' . $mailer->getSubject())
        ->setHtmlBody($mailer->getBody());

        $explodedTo = explode(';', $mailer->getRecipients());
        foreach ($explodedTo as $email) {
            if (!empty($email)) {
                $this->mail->addTo($email);
            }
        }

        $this->mail->send();
    }
}
