<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * LLAMADAS
 */
//MAIL LOG
//create msg for log1 send

$logMail = new LogMsg();
$logMail->setLogFile($logFile);
$logMail->setProjectName($projectName);
$logMail->setProject($project);
//envio del mensaje de log1 creado 
$mailHelper = new BasicMailSevrice();
$mailHelper->send($logMail);

//create msg for log2 send (default)
$logMailDefault = new LogMsg();
//envio del mensaje de log2 creado 
$mailHelper = new BasicMailSevrice();
$mailHelper->send($logMailDefault);


//MAIL TAG
//create msg for tag1 send
$tagMail = new TagMsg();
$tagMail->setBody("Tag message");
//envio del mensaje de tag1 creado
$mailHelper = new BasicMailSevrice();
$mailHelper->send($tagMail);


//create msg for tag2 send (default)
$tagMail = new TagMsg();
//envio del mensaje de tag2 creado
$mailHelper = new BasicMailSevrice();
$mailHelper->send($tagMail);