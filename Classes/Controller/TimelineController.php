<?php

class Tx_SchedulerTimeline_Controller_TimelineController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     * @var string Key of the extension this controller belongs to
     */
    protected $extensionName = 'SchedulerTimeline';

    /**
     * @var t3lib_PageRenderer
     */
    protected $pageRenderer;

   	/**
	 * @var Tx_SchedulerTimeline_Domain_Repository_LogRepository
	 */
	protected $logRepository;

   	/**
	 * @var Tx_SchedulerTimeline_Domain_Repository_TaskRepository
	 */
	protected $taskRepository;

    /**
     * Initializes the controller before invoking an action method.
     *
     * @return void
     */
    protected function initializeAction() {
        $this->pageRenderer->addInlineLanguageLabelFile('EXT:scheduler_timeline/Resources/Private/Language/locallang.xml');
        $this->pageRenderer->addCssFile(t3lib_extMgm::extRelPath('scheduler_timeline') . 'Resources/Public/StyleSheet/module.css');
        $this->pageRenderer->addJsLibrary('jquery', t3lib_extMgm::extRelPath('scheduler_timeline') . 'Resources/Public/JavaScript/jquery-1.6.2.min.js');
        $this->pageRenderer->addJsFile(t3lib_extMgm::extRelPath('scheduler_timeline') . 'Resources/Public/JavaScript/common.js');
    }

	/**
	 * @param Tx_SchedulerTimeline_Domain_Repository_LogRepository $logRepository
	 * @return void
	 */
	public function injectLogRepository(Tx_SchedulerTimeline_Domain_Repository_LogRepository $logRepository) {
		$this->logRepository = $logRepository;
	}

	/**
	 * @param Tx_SchedulerTimeline_Domain_Repository_LogRepository $logRepository
	 * @return void
	 */
	public function injectTaskRepository(Tx_SchedulerTimeline_Domain_Repository_TaskRepository $taskRepository) {
		$this->taskRepository = $taskRepository;
	}

    /**
     * Simple action to list some stuff
     */
    public function timelineAction() {

    	$starttime = mktime(date('H', $GLOBALS['EXEC_TIME'])-2, 0, 0);
    	$endtime = mktime(date('H', $GLOBALS['EXEC_TIME'])+1, 0, 0);

    	$intervals = array();

    	for ($i = $starttime; $i<$endtime; $i+=60*60) {
    		$intervals[] = date('H', $i);
    	}

		$logs = $this->logRepository->findByTime($starttime, $endtime); /* @var $logs Tx_Extbase_Persistence_QueryResult */

		$this->view->assign('logs', $logs);
		$this->view->assign('intervals', $intervals);
		$this->view->assign('starttime', $starttime);
		$this->view->assign('zoom', 15); // amount of seconds per pixel
    }


    /**
     * Processes a general request. The result can be returned by altering the given response.
     *
     * @param Tx_Extbase_MVC_RequestInterface $request The request object
     * @param Tx_Extbase_MVC_ResponseInterface $response The response, modified by this handler
     * @throws Tx_Extbase_MVC_Exception_UnsupportedRequestType if the controller doesn't support the current request type
     * @return void
     */
    public function processRequest(Tx_Extbase_MVC_RequestInterface $request, Tx_Extbase_MVC_ResponseInterface $response) {
        $this->template = t3lib_div::makeInstance('template');
        $this->pageRenderer = $this->template->getPageRenderer();

        $GLOBALS['SOBE'] = new stdClass();
        $GLOBALS['SOBE']->doc = $this->template;

        parent::processRequest($request, $response);

        $pageHeader = $this->template->startpage(
            $GLOBALS['LANG']->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang.xml:module.title')
        );
        $pageEnd = $this->template->endPage();

        $response->setContent($pageHeader . $response->getContent() . $pageEnd);
    }

}
