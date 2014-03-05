<?php

namespace DixonsCz\Chuck\Jira\Response;

interface ITransformer
{
    
    /**
     * @param string $data
     * @return mixed
     */
    function createFromRawData($data);
    
}
