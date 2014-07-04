<?php

namespace DixonsCz\Chuck\Presenters;

/**
 * @author Michal Svec <michal.svec@dixonsretail.com>
 */
abstract class ProjectPresenter extends \DixonsCz\Chuck\Presenters\BasePresenter
{
    /** @var @var array */
    protected $projects;

    /** @persistent */
    public $action;

    /** @var array */
    protected $projectInfo = array();

    public function startup()
    {
        $this->projects = $this->context->parameters['projects'];
        $project = $this->getParameter('project');

        if (!array_key_exists($project, $this->projects)) {
            throw new \Nette\InvalidArgumentException("Invalid project");
        }

        $this->project = $project;

        $this->context->svnHelper->startup($project);
        $this->template->project = $project;
        $this->template->projectName = $this->projects[$this->project]['label'];

        $this->projectInfo = $this->context->svnHelper->getInfo();
        $this->template->projectRoot = $this->projectInfo['root'];
        $this->template->projectUrl = $this->projectInfo['url'];

        parent::startup();
    }

    /**
     * @return \DixonsCz\Chuck\Log\Processor
     */
    protected function getLogGenerator()
    {
        return $this->context->logProcessor;
    }

    /**
     * Gets file name for the template used set in config
     * @deprecated use Changelog\Processor
     * @param  string $project
     * @return string
     */
    protected function getTemplateForProject($project)
    {
        if (isset($this->context->parameters['projects'][$project]['changelogTpl'])) {
            return $this->context->parameters['projects'][$project]['changelogTpl'];
        } else {
            return $this->context->parameters['changelogWikiTpl'];
        }
    }

    /**
     * Format whole array of log messages
     * @deprecated use Changelog\Processor
     * @param   array
     * @return array
     */
    protected function formatLog($log)
    {
        $logFormatted = array();

        foreach ($log as $revision) {
            $revisionData = $this->getLogGenerator()->parseRevisionMessage($revision['msg']);

            $revision['ticket'] = $revisionData['ticket'];
            //$revision['type'] = $revisionData['type'];
            $revision['message'] = $revisionData['message'];

            $logFormatted[] = $revision;
        }

        return $logFormatted;
    }

    /**
     * @deprecated use Changelog\Processor
     * @param $template
     * @param $ticketLog
     * @return \Nette\Templating\FileTemplate
     */
    protected function getChangelogTemplate($template, $ticketLog)
    {
        // #necessary to create new template, because $this->createTemplate() needs block and includes layout file
        $template = new \Nette\Templating\FileTemplate(
            APP_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Log' . DIRECTORY_SEPARATOR . 'changelogTpls' . DIRECTORY_SEPARATOR . $template
        );
        $template->onPrepareFilters[] = callback($this, 'templatePrepareFilters');
        $template->registerHelperLoader('Nette\Templating\Helpers::loader');
        $template->baseUri = $template->baseUrl = rtrim($this->getHttpRequest()->getUrl()->getBaseUrl(), '/');
        $template->basePath = preg_replace('#https?://[^/]+#A', '', $template->baseUri);
        $template->ticketLog = $ticketLog;
        $template->jiraPath = $this->context->parameters['jiraPath'];

        return $template;
    }

    /**
     * Get an email to send changelog to.
     *
     * Check for value in project settings or use default one from config.neon
     *
     * @param  string $project
     * @return string
     */
    protected function getSendToMail($project)
    {
        if(isset($this->context->parameters['projects'][$this->project]['sendTo'])) {
            return $this->context->parameters['projects'][$this->project]['sendTo'];
        }
        else {
            return $this->context->parameters['changelogSendTo'];
        }
    }
}
