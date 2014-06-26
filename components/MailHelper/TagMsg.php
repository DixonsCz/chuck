<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TagMsg
 *
 * @author nano
 */
class TagMsg implements MsgContainer {
    
    /**
     *
     * @var string 
     */
    private $body;
    /**
     *
     * @var string 
     */
    private $subject;
    /**
     *
     * @var array 
     */
    private $recipients;

    public function __construct() {
        $this->recipients = array();
    }

    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getSubject() {
        return $this->subject;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getBody() {
        if(empty($this->body)){
            $this->body = 'DEFAULT MESSAGE';
        }
        return $this->body;
    }

    public function setRecipients($recipients) {
        $this->recipients = $recipients;
        return $this;
    }

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getRecipients() {
        return $this->recipients;
    }
    
}
