<?php
namespace DixonsCz\Chuck\Svn;

class FromFile implements IHelper
{
    /**
     *
     * @var string
     */
    protected $svnLogFile;

    /**
     *
     * @var string
     */
    public $currentBranch = 'trunk';

    /**
     *
     * @param string $logPath
     */
    public function __construct($logPath)
    {
        $this->svnLogFile = $logPath;
    }

    /**
     *
     * @param string $tagName
     * @param string $tagMessage
     */
    public function createTag($tagName, $tagMessage)
    {

    }

    /**
     *
     * @return string
     */
    public function getCurrentBranch()
    {
        return $this->currentBranch;
    }

    /**
     *
     * @param string
     */
    public function setCurrentBranch($currentBranch)
    {
        $this->currentBranch = $currentBranch;
    }

    /**
     *
     * @param string $project
     * @return array
     */
    public function getInfo($project = null)
    {
        return array(
            'url' => 'http://really-fake-repo',
            'root' => 'fake-root',
        );
    }

    /**
     *
     * @param string $path
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getLog($path = '/trunk', $offset = 0, $limit = 30)
    {
        $xmlLog = simplexml_load_file($this->svnLogFile);

        $output = array();
        foreach ($xmlLog as $log)
        {
            $output[(int) $log->attributes()->revision] = array(
                'revision' => (int) $log->attributes()->revision,
                'author' => (string) $log->author,
                'date' => (string) $log->date,
                'msg' => (string) $log->msg,
            );
        }

        return $output;
    }

    /**
     *
     * @return int
     */
    public function getLogSize()
    {
        return 30;
    }

    /**
     *
     * @return array
     */
    public function getTagList()
    {
        return array();
    }

    /**
     *
     * @param string $tagName
     * @param int $limit
     * @return array
     */
    public function getTagLog($tagName, $limit = 30)
    {
        return $this->getLog();
    }

    /**
     *
     * @param string $project
     */
    public function startup($project)
    {

    }

    public function updateRepository()
    {

    }

    /**
     *
     * @return array
     */
    public function getBranchesList()
    {
        $branches = __DIR__ . '/../../app/resources/branches.xml';
        $xmlBranches = simplexml_load_file($branches);

        $output[$this->currentBranch] = $this->currentBranch;
        if ($xmlBranches->list) {
            foreach ($xmlBranches->list->entry as $branch) {
                if ($branch->attributes()->kind == 'dir') {
                    $output[(string) $branch->name] = (string) $branch->name;
                }
            }
        }

        return $output;
    }

}