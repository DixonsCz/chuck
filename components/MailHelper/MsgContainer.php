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
interface MsgContainer {
    
    public function getSubject();

    public function getBody();

    public function getRecipients();
}
