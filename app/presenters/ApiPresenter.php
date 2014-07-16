<?php

namespace DixonsCz\Chuck\Presenters;


class ApiPresenter extends ProjectPresenter
{
    /**
     * @var \DixonsCz\Chuck\Svn\Helper
     */
    private $svn;

    /**
     * @var \DixonsCz\Chuck\Api\ApiService
     */
    private $service;

    public function __construct(\DixonsCz\Chuck\Svn\IHelper $svnHelper, \DixonsCz\Chuck\Api\ApiService $service)
    {
        $this->svn = $svnHelper;
        $this->service = $service;
    }

    /**
     * @param string $status status token OK/NOK
     * @param string $response
     * @throws \Nette\Application\AbortException
     */
    private function sendApiResponse($status, $response)
    {
        switch($this->getParameter('format')) {
            case "raw":
                parent::sendResponse(new \Nette\Application\Responses\TextResponse($response));
                break;

            case "json":
            default:
                parent::sendJson(array(
                    'status' => $status,
                    'message' => $response,
                ));
        }

        $this->terminate();
    }

    /**
     * POST /api/<application>/uat-tags/<tag-name>  Creates new tag
     * GET  /api/<application>/uat-tags/            lists uat tags
     *
     * @param  string $project
     * @param  string|NULL $id
     * @throws \DixonsCz\Chuck\Api\InvalidMethodException
     */
    public function actionUatTags($project, $id = null)
    {
        $response = "";
        $tag = $id;

        try {
            switch ($this->getRequest()->getMethod()) {
                case "POST":
                    if(!is_string($id)) {
                        throw new \InvalidArgumentException("Missing tag name");
                    }
                    $response = $this->service->createTag($project, "UAT", $tag);
                    break;

                case "GET":
                    $response = $this->service->listTags($project, 'UAT');
                    break;

                default:
                    throw new \DixonsCz\Chuck\Api\InvalidMethodException("Unsupported HTTP method.");
            }
        } catch(\Exception $e) {
            $this->sendApiResponse('NOK', $e->getMessage());
        }

        $this->sendApiResponse('OK', $response);
    }

    /**
     * GET /api/<application>/history/<tag-name> returns history for tag in given application
     *
     * @param  string $project
     * @param  string|null $id
     * @throws \DixonsCz\Chuck\Api\InvalidMethodException
     */
    public function actionHistory($project, $id = null)
    {
        $tag = $id;

        $response = "";
        try {
            switch ($this->getRequest()->getMethod()) {
                case "GET":

                    switch ($this->getParameter('template')) {
                        case 'wiki':
                        case 'html':
                            $response = $this->service->getTagHistory($project, $tag, $this->getParameter('template'));
                            break;
                        default:
                            $response = $this->service->getTagHistory($project, $tag);
                    }

                    break;

                default:
                    throw new \DixonsCz\Chuck\Api\InvalidMethodException("Unsupported HTTP method.");
            }
        } catch(\Exception $e) {
            $this->sendApiResponse('NOK', $e->getMessage());
        }

        $this->sendApiResponse('OK', $response);
    }

    /**
     * Sends UAT release mail to addresses defined in configuration file
     *
     * POST /api/<application>/uat-release-mail/<tag-name>
     *
     * @param string $project
     * @param string $id
     */
    public function actionUatReleaseMail($project, $id)
    {
        $tag = $id;
        $response = "";
        try {
            switch ($this->getRequest()->getMethod()) {
                case "POST":
                    $response = $this->service->sendUatReleaseMail($project, $tag);
                    break;

                default:
                    throw new \DixonsCz\Chuck\Api\InvalidMethodException("Unsupported HTTP method.");
            }
        } catch(\Exception $e) {
            $this->sendApiResponse('NOK', $e->getMessage());
        }

        $this->sendApiResponse('OK', $response);
    }
}
