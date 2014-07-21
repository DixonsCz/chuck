<?php

namespace DixonsCz\Chuck\Api;


class ApiService
{
    /**
     * @var \DixonsCz\Chuck\Svn\Helper
     */
    private $helper;

    /**
     * @var \DixonsCz\Chuck\Changelog\Formatter
     */
    private $tplFormatter;

    /**
     * @var \DixonsCz\Chuck\Log\Processor
     */
    private $logProcessor;

    /**
     * @var \DixonsCz\Email\Mailer
     */
    private $mailer;

    public function __construct(
        \DixonsCz\Chuck\Svn\IHelper $svnHelper,
        \DixonsCz\Chuck\Log\Processor $logProcessor,
        \DixonsCz\Chuck\Changelog\IFormatter $tplFormatter,
        \DixonsCz\Email\IMailer $mailer
    ) {
        $this->helper = $svnHelper;
        $this->tplFormatter = $tplFormatter;
        $this->logProcessor = $logProcessor;
        $this->mailer = $mailer;
    }

    /**
     * @param string $project
     * @param string $pattern preg_replace pattern without delimiters
     * @return array list of tags matching pattern
     */
    public function listTags($project, $pattern = '.*')
    {
        $tags =  $this->helper->getTagList($project);

        return array_filter($tags, function ($item) use ($pattern) {
            return 1 === preg_match("~{$pattern}~", $item['name']);
        });
    }

    /**
     * @param string $project
     * @param string $sourceBranch {UAT, PROD}
     * @param string $tagName
     * @return string svn message
     * @throws SvnException
     */
    public function createTag($project, $sourceBranch, $tagName)
    {
        if(!$this->helper->doesBranchExist($project, $sourceBranch)) {
            throw new SvnException("Source branch doesn't exist");
        }

        if($this->helper->doesTagExist($project, $tagName)) {
            throw new SvnException("Tag already exists");
        }

        return $this->helper->createTag($tagName, "Creating: {$tagName} from {$sourceBranch}", "branches/{$sourceBranch}");
    }

    /**
     * @param string $project
     * @param string $tagName
     * @param string
     * @return mixed
     */
    public function getTagHistory($project, $tagName, $template = 'html')
    {
        $logList = $this->helper->getUATTagChangelog($tagName);
        $ticketList = $this->logProcessor->generateTicketLog($logList);

        if($template == 'none') {
            return $ticketList;
        }

        return $this->tplFormatter->formatLog($project, array(
                'ticketLog' => $ticketList,
                'tag' => $tagName,
            ), $template);
    }

    /**
     * @param $project
     * @param $tagName
     * @return bool
     */
    public function sendUatReleaseMail($project, $tagName)
    {
        $logList = $this->helper->getUATTagChangelog($tagName);
        $ticketList = $this->logProcessor->generateTicketLog($logList);
        $formatted = $this->tplFormatter->formatLog($project, array(
                'ticketLog' => $ticketList,
                'tag' => $tagName,
            ), 'html');

        $this->mailer->sendUatChangelog($formatted);

        return "Mail sent";
    }
}
