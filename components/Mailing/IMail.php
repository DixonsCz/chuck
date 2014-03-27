<?
namespace DixonsCz\Chuck\Mailing;

/**
 * Mail interface
 *
 * @author Libor Pichler
 *
 */

interface IMail 
{
   /**
     * Setter
     * @param $templateName
     * @return void
     */
    public function setTemplateName($templateName);

    /**
     * Getter
     * @return string
     */
    public function getTemplateName();

    /**
     * get full path for template file
     * @return string
     */
    public function getTemplateNamePath();

    /**
     * initialize template engine as nested object $this->template
     * @return void
     */
    private function initTemplateEngine();

    /**
     * assign template object to mail
     * @return void
     */

    public function initBody();

    /**
     * Getter
     * @return string
     */
    public function getTemplate();

    /**
     * assign $name variable to template object
     * @param string $name - name of variable in template
     * @param string $data - data for $name variable
     * @return void
     */
    public function assingVar($name, $data);

    /**
     * Setter
     * @param $jiraPath
     * @return void
     */
    public function setJiraPath($jiraPath);

    /**
     * Setter
     * @param $log
     * @return void
     */
    public function setLog($log);

    /**
     * Setter
     * @param $project
     * @return void
     */
    public function setProject($project);
     
    /**
     * Setter
     * @param $projectName
     * @return void
     */
    public function setProjectName($projectName);
}