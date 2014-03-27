<?
namespace DixonsCz\Chuck\Mailing;

/**
 * Sends emails according to inherited mailer
 *
 * @author Libor Pichler
 *
 */

class Mailer extends \Nette\Mail\SendmailMailer implements IMailer{

    /**
      * Sends email.
      *
      * @param \Nette\Mail\Message or \DixonsCz\Chuck\Mailing\Mail
      * @return void
     */
    public function send(\Nette\Mail\Message $mail) {
        if (method_exists($mail, "initBody")) {
            $mail->initBody();
        }
        return parent::send($mail);
    }
} 