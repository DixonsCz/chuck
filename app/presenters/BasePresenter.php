<?php

namespace DixonsCz\Chuck\Presenters;

/**
 * @author Michal Svec <michal.svec@dixonsretail.com>
 *
 * @property \stdClass $template
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
    /** @var @persistent */
    public $project;

    protected function createComponentProjectForm($name)
    {
        $form = new \Nette\Application\UI\Form($this, $name);
        $form->getElementPrototype()->class[] = "form-horizontal";

        $form->addSelect('project', "", $this->getProjectList(true));
        $form->addSubmit('submitButton', 'Select')->setAttribute('class', 'btn btn-primary');
        $form->onSuccess[] = callback($this, 'projectFormSubmitted');

        $form->setDefaults(array(
            'project' => $this->getParameter('project')
        ));

        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     */
    public function projectFormSubmitted($form)
    {
        $values = $form->getValues();

        $this->redirect("Info:default", array('project' => $values['project']));
    }

    protected function beforeRender()
    {
        $this->template->projectMenu = array(
            'Info:default' => 'Info',
//            'Tag:list' => 'Tags',     // @depracated
            'Log:list' => 'Log',
        );

        $this->template->cdnUrl = $this->context->parameters['cdnUrl'];

        parent::beforeRender();
    }

    /**
     * Return project information from configuration file
     *
     * @param  bool  $bReturnPairs return key value pair for select tag, otherwise return array with all data
     * @return array
     */
    protected function getProjectList($bReturnPairs = false)
    {
        if (true === $bReturnPairs) {
            $aProjectPairs = array();
            foreach ($this->context->parameters['projects'] as $sKey => $aInfo) {
                $aProjectPairs[$sKey] = $aInfo['label'];
            }

            return $aProjectPairs;
        }

        return $this->context->parameters['projects'];
    }
}
