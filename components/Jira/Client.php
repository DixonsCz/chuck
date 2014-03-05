<?php

namespace DixonsCz\Chuck\Jira;

class Client implements IClient
{
    
    /**
     *
     * @var IConfiguration
     */
    protected $configuration;
    
    /**
     * 
     * @param IConfiguration $config
     */
    public function __construct(IConfiguration $config)
    {
        $this->configuration = $config;
    }
    
    /**
     * 
     * @param string $path
     * @return \JiraResponse
     */
    public function requestPath($path)
    {
        $requestUrl = $this->configuration->getApiUrl() . $path;
        $request = new \sKdyby\Curl\Request($requestUrl);

        $request->headers['Authorization'] = 'Basic ' . base64_encode("{$this->configuration->getUsername()}:{$this->configuration->getPassword()}");
        $request->setFollowRedirects(TRUE);
        $responseBody = $request->get()->getResponse();
        return new JiraResponse($responseBody);
    }

}