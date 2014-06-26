<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LogMsg
 *
 * @author nano
 */
class LogMsg implements MsgContainer{
        
    /**
     * Path to JIRA
     * @var string
     */
    private $jira = 'http://jira.dixons.co.uk/';
    
    /**
     *
     * @var string 
     */
    private $logFile;
    
    /**
     *
     * @var string
     */
    private $projectName;
    
    /** @var string*/
    private $type;
    
    /**
     *
     * @var string
     */
    private $project;

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getSubject() {
        return "Log Message from " . date("Y/m/d H:i:s");
    }

    public function setLogFile($logFile) {
        $this->logFile = $logFile;
        return $this;
    }

    public function setProjectName($projectName) {
        $this->projectName = $projectName;
        return $this;
    }
    
    public function setProject($project) {
        $this->project = $project;
        return $this;
    }
    
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getBody() {
        if(empty($this->logFile)){
            $this->logFile = 'DEFAULT LOG';
        }
        if(empty($this->projectName)){
            $this->projectName = 'DEFAULT PROJECTNAME';
        }
        
        if(empty($this->project)){
            $this->project = 'DEFAULT PROJECT';
        }
        return $this->getTemplate($this->logFile, $this->projectName,  $this->project);
    }

    /**
     * Parsea la plantilla
     * Debe hacer algo parecido al método getTemplate que tienen ellos en 
     * su clase MailHelper
     */
    protected function getTemplate($formatedLog, $projectName, $project)
    {
        $template = new FileTemplate(__DIR__ . '/'. $this->getTemplateName());
        $template->registerFilter(new Engine);
        $template->registerHelperLoader('Nette\Templating\Helpers::loader');
        $template->jira    = $this->jira;
        $template->log         = $formatedLog;
        $template->projectName = $projectName;
        $template->project     = $project;

        return $template;
    }
    
    /**
     * Get template name
     * @return string
     */
    public function getTemplateName()
    {
        return $this->type . '.latte';
    }

    /**
     * Método obligatorio por definición en interfaz MsgContainer
     */
    public function getRecipients() {
        return array("devops@mycompany.com");
    }

     

}
