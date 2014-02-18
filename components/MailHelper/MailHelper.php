<?php

use Nette\Mail\Message,
	Nette\Templating\FileTemplate,
	Nette\Latte\Engine;

/**
 *
 * @author Petr Jurasek <petr.jurasek@dixonsretail.com>
 */
class MailHelper 
{
	/* template constants */
	const TEMPLATE_ONE_LINE = 'Line';
	const TEMPLATE_TABLE    = 'Table';

	/** 
	 * Path to JIRA
	 * @var string
	 */
	private $jiraPath = 'http://jira.dixons.co.uk/';

	/** @var string*/
	private $type;

	/** @var \Nette\Mail\Message */
	private $mail;


	/**
	 * Return mail
	 * 
	 * @param array  $formatedLog Formated log
	 * @param string $projectName Project name
	 * @param string $project     Project
	 * @param string $to          Recepients
	 * @return \Nette\Mail\Message $mail
	 */
	public function getMail($formatedLog, $projectName, $project, $to, $type = self::TEMPLATE_TABLE)
	{
		$this->type = $type;
		$this->mail = new Message;
		$this->mail->setFrom('Chuck Norris <no-reply@dixonsretail.com>')
		->addTo('petr.jurasek@dixonsretail.com')
		->setSubject('[Release note] ' . $projectName)
		->setHtmlBody($this->getTemplate($formatedLog, $projectName, $project));

		$explodedTo = explode(';', $to);
		foreach ($explodedTo as $email)
		{
			if(!empty($email)) {
				$this->mail->addTo($email);
			}
		}

		return $this->mail->send();
	}


	/**
	 * Return mail template
	 * 
	 * @param array  $formatedLog Formated log
	 * @param string $projectName Project name
	 * @param string $project     Project
	 * @return \Nette\Templating\FileTemplate $template
	 */
	public function getTemplate($formatedLog, $projectName, $project)
	{
		$template = new FileTemplate(__DIR__ . '/'. $this->getTemplateName());
		$template->registerFilter(new Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->jiraPath    = $this->jiraPath;
		$template->log         = $formatedLog;
		$template->projectName = $projectName;
		$template->project     = $project;
		return $template;
	}


	/**
	 * Get template name
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->type . '.latte';
	}

	/**
	 * Send mail
	 * @return void
	 */
	public function send()
	{
		return $this->mail->send();
	}

}
