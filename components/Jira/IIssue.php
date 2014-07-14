<?php

namespace DixonsCz\Chuck\Jira;

interface IIssue
{
    
    /**
     * @return mixed[]
     */
    public function toArray();
    
    /**
     * @return bool
     */
    public function isRFC();
    
    /**
     * @return bool
     */
    public function isBug();
    
    /**
     * @return bool
     */
    public function isSupportRequest();
    
    /**
     * @return bool
     */
    public function isOther();
    
    /**
     * @param \DixonsCz\Chuck\Svn\IRevisionMessage $message
     */
    public function attachRevisionMessage(\DixonsCz\Chuck\Svn\IRevisionMessage $message);
    
}