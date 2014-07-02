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
    public function sendApiResponse($status, $response)
    {
        switch($this->getParameter('format')) {
            case "raw":
                \Nette\Diagnostics\Debugger::$bar = FALSE;
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
     * POST: Creates new tag
     * GET: get tag list
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
     * Gets history for tag
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
                    $response = $this->service->getTagHistory($project, $tag, true);
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
