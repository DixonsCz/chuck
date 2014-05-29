<?php

namespace DixonsCz\Chuck\Presenters;

/**
 * @author Michal Svec <michal.svec@dixonsretail.com>
 */
class LogPresenter extends ProjectPresenter
{

    /**
    *
    * @var \Nette\Http\SessionSection
    */
    public $logSession;

    public function __construct(\Nette\Http\Session $session)
    {
        parent::__construct();

        $this->logSession = new \Nette\Http\SessionSection($session, 'logSession');
    }

    /**
     * Renders list of log with a visual paginator
     *
     * @param string $project
     */
    public function renderList($project)
    {
        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = 30;
        $paginator->itemCount = $this->context->svnHelper->getLogSize();
        $this->template->vpPage = $paginator->page;

        $this->template->mailTo = "";
        if (isset($this->context->parameters['projects'][$this->project]['sendTo'])) {
            $this->template->mailTo = $this->context->parameters['projects'][$this->project]['sendTo'];
        }

        if ($this->logSession->offsetExists('currentBranch')) {
            $this->template->currentBranch = $this->logSession->offsetGet('currentBranch');
        } else {
            $this->template->currentBranch = $this->context->svnHelper->getCurrentBranch();
        }

        try {
            $logList = $this->context->svnHelper->getLog(
                $this->template->currentBranch,
                $paginator->offset,
                $paginator->itemsPerPage
            );
        } catch (\Exception $e) {
            $logList = array();
            $this->flashMessage($e->getMessage(), 'error');
        }

//             $this->template->ticketLog = false;
        $this->template->logTpl = "";

        // generating log for confluence
        $selectedLogs = array();
        if (isset($_GET['log']) && !empty($_GET['log'])) {

            $changeLogList = array();
            foreach ($_GET['log'] as $revision) {
                $changeLogList[$revision] = $logList[$revision];
                $selectedLogs[] = $revision;
            }

            if (isset($_GET['emailSend']) && $_GET['emailSend'] == 'email') {
                $this->context->mailHelper->getMail($this->formatLog($changeLogList), $this->template->projectName, $this->project, $_GET['toReleaseNote'], 'Line');
                $this->flashMessage('Mail was sent!', 'success');
            }

            $this->template->logTpl = $this->getChangelogTemplate($this->getTemplateForProject($project), $this->getLogGenerator()->generateTicketLog($changeLogList));
        }

        \Nette\Diagnostics\Debugger::barDump($logList, "Log list");

        $logTable = new \DixonsCz\Chuck\Log\Table($this, 'logTable');
        $logTable->setLog($this->formatLog($logList));
        $logTable->setSelectedLogs($selectedLogs);

        $this->template->log = array();
    }

    /**
     * @param string
     */
    protected function createComponentBranchForm($name)
    {
        $form = new \Nette\Application\UI\Form($this, $name);
        $form->getElementPrototype()->class[] = "form-horizontal";

        $form->addSelect('branch', "", $this->context->svnHelper->getBranchesList());
        $form->addSubmit('submitButton', 'Select a branch')->setAttribute('class', 'btn');
        $form->onSuccess[] = new \Nette\Callback($this, 'branchFormSubmitted');

        $form->setDefaults(array(
            'branch' => $this->context->svnHelper->getCurrentBranch()
        ));

        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function branchFormSubmitted($form)
    {
        $values = $form->getValues();

        $this->context->svnHelper->setCurrentBranch($values['branch']);
        $this->logSession->offsetSet('currentBranch', $this->context->svnHelper->getCurrentBranch());
    }
}