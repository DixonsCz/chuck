<?php

namespace DixonsCz\Chuck\Svn\RevisionMessage;

interface IParser
{
    /**
     * @param string $message
     * @return \DixonsCz\Chuck\Svn\IRevisionMessage
     */
    public function parseFromString($message);
}