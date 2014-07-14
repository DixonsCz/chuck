<?php

namespace DixonsCz\Chuck\Jira;

interface IResponse
{
    
    /**
     * @param Response\ITransformer $transformer
     * @return mixed
     */
    public function transform(Response\ITransformer $transformer);
    
}
