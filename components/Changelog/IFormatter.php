<?php

namespace DixonsCz\Chuck\Changelog;

interface IFormatter
{
    /**
     * Generates HTML/markup template for project
     *
     * @param string $project
     * @param array $log
     * @return string
     */
    public function formatLog($project, array $log);
}
