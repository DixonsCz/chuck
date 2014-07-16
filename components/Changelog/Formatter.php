<?php

namespace DixonsCz\Chuck\Changelog;


class Formatter implements IFormatter
{
    /**
     * @var array
     */
    private $projects;

    /**
     * @var array
     */
    private $tplVariables;

    /**
     * @var string
     */
    private $templateDir;

    /**
     * @param string $templateDir
     * @param array $projects project configuration
     * @param array $tplVariables variables passed to changelog template
     */
    public function __construct($templateDir, array $projects, $tplVariables = array())
    {
        if(!is_dir($templateDir)) {
            throw new \InvalidArgumentException("Invalid template directory");
        }

        $this->templateDir = $templateDir;
        $this->projects = $projects;
        $this->tplVariables = $tplVariables;
    }

    /**
     * {@inheritdoc}
     */
    public function formatLog($project, array $parameters, $format = 'wiki')
    {
        $template = $this->createTemplate($format);

        $template->project = $project;
        foreach($parameters as $key => $var) {
            $template->$key = $var;
        }

        foreach($this->tplVariables as $key => $var) {
            $template->$key = $var;
        }

        return (string) $template;
    }

    /**
     * Creates Latte template to generate changelog
     *
     * @param  string $format
     * @return \Nette\Templating\FileTemplate
     */
    private function createTemplate($format = 'html')
    {
        $template = new \Nette\Templating\FileTemplate($this->templateDir.'/'.$format.'.latte');
        $template->registerFilter(new \Nette\Latte\Engine());

        return $template;
    }
}
