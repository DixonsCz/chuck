<?php

namespace DixonsCz\Chuck\Changelog;

interface IFormatter
{
    /**
     * Generates HTML/markup template for project
     *
     * @param string $project
     * @param array $parameters  key is a variable name in template, value is passed to template
     * @return string
     */
    public function formatLog($project, array $parameters);
}
