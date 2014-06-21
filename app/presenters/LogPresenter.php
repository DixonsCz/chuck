<?php

namespace DixonsCz\Chuck\Presenters;

use DixonsCz\Chuck\Svn\Helper;
use Nette\Http\IRequest;

/**
 * @author Michal Svec <michal.svec@dixonsretail.com>
 * @author Martin Gregor <greczsky@gmail.com>
 */
class LogPresenter extends ProjectPresenter
{
	/** @var svnHelper */
	protected $svnHelper;

	/** @var httpRequest */
	protected $httpRequest;

	/** @var mailHelper */
	protected $mailHelper;

	private $paginator;
	private $logTable;

	public function startup()
	{
		parent::startup();

		$vp 				= new \VisualPaginator($this, 'vp');
		$this->paginator 	= $vp->getPaginator();
		$this->paginator->itemsPerPage 	= 30;
		$this->paginator->itemCount 	= $this->svnHelper->getLogSize();

		$this->logTable = new \DixonsCz\Chuck\Log\Table($this, 'logTable');

	}

	public function injectSvnHelper(DixonsCz\Chuck\Svn\Helper $svnHelper)
	{
		$this->svnHelper = $svnHelper;
	}

	public function injectHttpRequest(IRequest $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}

	public function injectMailHelper(\MailHelper $mailHelper)
	{
		$this->mailHelper = $mailHelper;
	}

    /**
     * Renders list of log with a visual paginator
     *
     * @param string $project
     */
    public function renderList($project)
    {
        $this->template->vpPage = $this->paginator->page;
        $this->template->mailTo = isset($this->context->parameters['projects'][$this->project]['sendTo']) ? $this->context->parameters['projects'][$this->project]['sendTo'] : "";
		$this->template->logTpl = $this->fillLogList($project);
        $this->template->log 	= array();
    }

	private function fillLogList($project)
	{
		try {
			$logList = $this->svnHelper->getLog(
				$this->svnHelper->getCurrentBranch(),
				$this->paginator->offset,
				$this->paginator->itemsPerPage
			);
		} catch (\Exception $e) {
			$logList = array();
			$this->flashMessage($e->getMessage(), 'error');
		}

		\Nette\Diagnostics\Debugger::barDump($logList, "Log list");

		// generating log for confluence
		$selectedLogs = array();
		if ($logs = $this->httpRequest->getquery('log')) {

			$changeLogList = array();
			foreach ($logs as $revision) {
				$changeLogList[$revision] = $logList[$revision];
				$selectedLogs[] = $revision;
			}

			if ($this->httpRequest->getquery('emailSend') == 'email') {
				$this->mailHelper->getMail($this->formatLog($changeLogList), $this->template->projectName, $this->project, $this->httpRequest->getquery('toReleaseNote'), 'Line');
				$this->flashMessage('Mail was sent!', 'success');
			}

			$this->logTable->setLog($this->formatLog($logList));
			$this->logTable->setSelectedLogs($selectedLogs);

			return $this->getChangelogTemplate($this->getTemplateForProject($project), $this->getLogGenerator()->generateTicketLog($changeLogList));
		}
		return '';
	}
}
