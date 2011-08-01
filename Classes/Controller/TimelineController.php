<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Fabrizio Branca <typo3@fabrizio-branca.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * TimelineController
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
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

    	$hours = 25; // amount of hours to be displayed
    	$zoom = 15; // amount of seconds per pixel

    	$pixelsPerMinute = 60 / $zoom;
    	$pixelsPerHour = 60 * $pixelsPerMinute;

    	$starttime = mktime(date('H', $GLOBALS['EXEC_TIME'])-($hours-1), 0, 0);
    	$endtime = mktime(date('H', $GLOBALS['EXEC_TIME'])+1, 0, 0);

		// y-Axis labels (could be done in a viewhelper)
    	$dateFormat = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] . ' ' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'];
    	$intervals = array();
    	for ($i = $starttime; $i<$endtime; $i+=60*60) {
    		$intervals[] = date($dateFormat, $i);
    	}

		$logs = $this->logRepository->findByTime($starttime, $endtime); /* @var $logs Tx_Extbase_Persistence_QueryResult */

		$now = ($GLOBALS['EXEC_TIME'] - $starttime) / $zoom;

		$this->view->assign('logs', $logs);
		$this->view->assign('intervals', $intervals);
		$this->view->assign('starttime', $starttime);
		$this->view->assign('zoom', $zoom);
		$this->view->assign('now', $now);
		$this->view->assign('timelinePanelWidth', $pixelsPerHour * $hours);
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
