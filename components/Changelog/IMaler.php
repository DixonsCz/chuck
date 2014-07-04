<?php

namespace DixonsCz\Email;

interface IMailer
{
    /**
     * Sends the email according to configuration file
     *
     * @param string $content content of the email message
     */
    public function sendUatChangelog($content);
}