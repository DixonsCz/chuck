<?
namespace DixonsCz\Chuck\Mailing;
/**
 * Mailer interface
 *
 * @author Libor Pichler
 *
 */
interface IMailer {
    
    /**
      * Sends email.
      *
      * @param \Nette\Mail\Message or \DixonsCz\Chuck\Mailing\Mail
      * @return void
     */
    public function send(\Nette\Mail\Message $mail);
}