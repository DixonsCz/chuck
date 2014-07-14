<?php

namespace DixonsCz\Chuck\Jira;

interface IClient
{
    
    /**
     * 
     * @param string $path
     * @return Response
     */
    public function requestPath($path);
    
}
