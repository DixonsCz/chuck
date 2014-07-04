<?php

namespace DixonsCz\Email;

class Mailer implements IMailer
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * @var \Nette\Mail\IMailer
     */
    private $mailer;

    public function __construct(array $configuration, \Nette\Mail\IMailer $mailer)
    {
        $this->configuration = $configuration;
        $this->mailer = $mailer;
    }

    /**
     * @return string
     */
    private function getSender()
    {
        return $this->configuration['sender'];
    }

    /**
     * @param  string $environment
     * @return array
     */
    private function getRecipients($environment)
    {
        return $this->configuration[$environment]['recipients'];
    }

    /**
     * @param  string $environment
     * @return string
     */
    private function getSubject($environment)
    {
        return $this->configuration[$environment]['subject'];
    }

    /**
     * {@inheritdoc}
     */
    public function sendUatChangelog($content)
    {
        $message = new \Nette\Mail\Message();
        $message->setFrom($this->getSender())
            ->setSubject($this->getSubject('uat'))
            ->setHTMLBody($content);

        foreach($this->getRecipients('uat') as $recipient) {
            $message->addTo($recipient);
        }

        $this->mailer->send($message);
    }
}
