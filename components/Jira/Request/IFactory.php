<?php

namespace DixonsCz\Chuck\Jira\Request;

interface IFactory
{
    
    /**
     * @param string $key
     * @return Issue
     */
    public function createIssueRequestByKey($key);
    
}