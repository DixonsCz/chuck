<?php

namespace DixonsCz\Chuck\Jira;

interface IRequest
{
    
    /**
     * @param IClient $client
     * @return IResponse
     */
    public function send(IClient $client);
    
}