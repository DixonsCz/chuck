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
    private $defaultWikiTpl;

    /**
     * @var string
     */
    private $defaultMailTpl;

    /**
     * @param string $changelogWiki
     * @param string $changelogMail
     * @param array $projects project configuration
     * @param array $tplVariables variables passed to changelog template
     */
    public function __construct($changelogWiki, $changelogMail, array $projects, $tplVariables = array())
    {
        $this->projects = $projects;
        $this->tplVariables = $tplVariables;
        $this->defaultMailTpl = $changelogMail;
        $this->defaultWikiTpl = $changelogWiki;
    }

    /**
     * Gets file name for the template used set in config
     *
     * @param  string $project
     * @return string
     */
    protected function getTemplateFile($project)
    {
        if (isset($this->projects[$project]['changelogTpl'])) {
            return $this->projects[$project]['changelogTpl'];
        } else {
            return $this->defaultWikiTpl;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function formatLog($project, array $log, $format = 'wiki')
    {
        $template = $this->createTemplate($project);
        $template->ticketLog = $log;
        foreach($this->tplVariables as $key => $var) {
            $template->$key = $var;
        }

        return (string) $template;
    }

    /**
     * Creates Latte template to generate changelog
     *
     * @param string $project
     * @return \Nette\Templating\FileTemplate
     */
    private function createTemplate($project)
    {
        $template = new \Nette\Templating\FileTemplate(APP_DIR . '/templates/Log/changelogTpls/'.$this->getTemplateFile($project));
        $template->registerFilter(new \Nette\Latte\Engine());

        return $template;
    }
}
