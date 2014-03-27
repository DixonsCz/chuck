<?
namespace DixonsCz\Chuck\Mailing;

/**
 * Base class for presentation layer of email
 *
 * @author Libor Pichler
 *
 */

class Mail extends \Nette\Mail\Message{
   
    const TEMPLATE_ONE_LINE = 'Line';
    const TEMPLATE_TABLE    = 'Table';

    private $templateName  = self::TEMPLATE_TABLE;
    private $template;

    function __construct() {
      parent::__construct();
      $this->initTemplateEngine();
      $this->setJiraPath('http://jira.dixons.co.uk/');
      $this->setFrom('no-reply@dixonsretail.com','Chuck Norris');
    }

    /**
     * Setter
     * @param string $templateName
     * @return void
     */
    public function setTemplateName($templateName) {
       $this->templateName = $templateName;
    }

    /**
     * Getter
     * @return string
     */
    public function getTemplateName() {
      return $this->templateName;
    }

    /**
     * get full path for template file
     * @return string
     */
    public function getTemplateNamePath() {
      return __DIR__ . '/templates/'. $this->getTemplateName().'.latte';
    }

    /**
     * initialize template engine as nested object $this->template
     * @return void
     */
    private function initTemplateEngine() {
        if (!($this->template instanceof Nette\Templating\IFileTemplate))
        {
            $this->template = new \Nette\Templating\FileTemplate($this->getTemplateNamePath());
            $this->template->registerFilter(new \Nette\Latte\Engine);
            $this->template->registerHelperLoader('Nette\Templating\Helpers::loader');
        }
    }

    /**
     * assign template object to mail
     * @return void
     */

    public function initBody(){
        $this->setHtmlBody($this->template);
    }

    /**
     * Getter
     * @return string
     */
    public function getTemplate() {
        return $template;
    }

    /**
     * assign $name variable to template object
     * @param string $name - name of variable in template
     * @param string $data - data for $name variable
     * @return void
     */
    public function assingVar($name, $data) {
        $this->template->$name = $data;
    }

    /**
     * Setter
     * @param string $jiraPath
     * @return void
     */
    public function setJiraPath($jiraPath) {
        $this->assingVar('jiraPath',$jiraPath);
    }

    /**
     * Setter
     * @param string $log
     * @return void
     */
    public function setLog($log) {
        $this->assingVar('log',$log);
    }

    /**
     * Setter
     * @param string $project
     * @return void
     */
    public function setProject($project) {
        $this->assingVar('project',$project);
    }
     
    /**
     * Setter
     * @param string $projectName
     * @return void
     */
    public function setProjectName($projectName) {
        $this->assingVar('projectName',$projectName);
    }

    /**
     * Add array of recipeients
     * @param array $recipients - array of email adress
     * @return void
     */
    public function addRecipients(array $recipients) {
        if (!empty($recipients))
        {
            foreach ($recipients as $recipient)
            {
                $this->addTo($recipient);
            }
        }
    }
}