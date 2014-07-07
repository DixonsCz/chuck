<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author nano
 */
interface IMailService {
    /**
     * @param string $subject
     * @param string $body
     * @param string[] $recipients
     */
    public function send(MsgContainer $mailer);
}
