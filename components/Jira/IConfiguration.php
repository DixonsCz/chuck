<?php

namespace DixonsCz\Chuck\Jira;

interface IConfiguration
{
    
    /**
     * 
     * @return string
     */
    public function getApiUrl();
    
    /**
     * 
     * @return string
     */
    public function getUsername();
    
    /**
     * 
     * @return string
     */
    public function getPassword();
    
}
