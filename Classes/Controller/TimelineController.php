<?php

namespace AOE\SchedulerTimeline\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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

use AOE\SchedulerTimeline\Converter\FormValueTimestampConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class TimelineController
 *
 * @package AOE\SchedulerTimeline\Controller
 */
class TimelineController extends ActionController
{

    /**
     * @var string Key of the extension this controller belongs to
     */
    protected $extensionName = 'SchedulerTimeline';

    /**
     * @var \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected $pageRenderer;

    /**
     * @var \AOE\SchedulerTimeline\Domain\Repository\LogRepository
     */
    protected $logRepository;

    /**
     * @var \AOE\SchedulerTimeline\Domain\Repository\TaskRepository
     */
    protected $taskRepository;

    /**
     * @var \TYPO3\CMS\Backend\Template\DocumentTemplate
     */
    protected $template;

    /**
     * Initializes the controller before invoking an action method.
     *
     * @return void
     */
    protected function initializeAction()
    {
        $this->pageRenderer->addInlineLanguageLabelFile('EXT:scheduler_timeline/Resources/Private/Language/locallang.xml');
    }

    /**
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer
     * @return void
     */
    public function injectPageRenderer(\TYPO3\CMS\Core\Page\PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * @param \AOE\SchedulerTimeline\Domain\Repository\LogRepository $logRepository
     * @return void
     */
    public function injectLogRepository(\AOE\SchedulerTimeline\Domain\Repository\LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * @param \AOE\SchedulerTimeline\Domain\Repository\TaskRepository $taskRepository
     * @return void
     */
    public function injectTaskRepository(\AOE\SchedulerTimeline\Domain\Repository\TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Simple action to list some stuff
     */
    public function timelineAction()
    {
        $zoom = 15; // amount of seconds per pixel

        $startTime = strtoTime('-1 hour');
        $endTime = time();
        $postVars = GeneralUtility::_POST('tx_schedulertimeline_tools_schedulertimelineschedulertimeline');
        $timespan = $postVars['timespan'] ?? null;
        if ($timespan !== null) {
            $startTime = $this->getStartTime($postVars['timespan']);
        }
        $groupedLogs = $this->logRepository->findGroupedByTask($startTime, $endTime);

        // To have logs that was added this second included,
        $startTimeFloor = $this->hourFloor($startTime);
        $endTimeCeil = $this->hourCeil($endTime);

        $this->view->assign('groupedLogs', $groupedLogs);
        $this->view->assign('starttime', $startTimeFloor);
        $this->view->assign('endtime', $endTimeCeil);
        $this->view->assign('zoom', $zoom);
        $this->view->assign('now', ($GLOBALS['EXEC_TIME'] - $startTimeFloor) / $zoom);
        $this->view->assign('timelinePanelWidth', ($endTimeCeil - $startTimeFloor) / $zoom);
        $this->view->assign('actionMenuValues', $this->getActionMenuValues());
        $this->view->assign('actionMenuSelected', $timespan);
    }

    /**
     * @param string $timespan
     * @return int
     */
    private function getStartTime(string $timespan)
    {
        $strToTimeValue = FormValueTimestampConverter::convertValueToStrToTimeValue($timespan);
        return strtotime('-' . $strToTimeValue);
    }

    /**
     * @return array
     */
    private function getActionMenuValues()
    {
        return [
            '1h' => '1 Hour',
            '3h' => '3 Hours',
            '1d' => '1 Day',
            '3d' => '3 Days',
            '1w' => '1 Week',
            '2w' => '2 Weeks',
            '3w' => '3 Weeks',
            '4w' => '4 Weeks',
        ];
    }

    /**
     * Return the last full houd
     *
     * @param int $timestamp
     * @return int
     */
    protected function hourFloor($timestamp)
    {
        return mktime(date('H', $timestamp), 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
    }

    /**
     * Returns the next full hour
     *
     * @param int $timestamp
     * @return int
     */
    protected function hourCeil($timestamp)
    {
        return mktime(date('H', $timestamp)+1, 0, 0, date('n', $timestamp), date('j', $timestamp), date('Y', $timestamp));
    }


    /**
     * Processes a general request. The result can be returned by altering the given response.
     *
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request The request object
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response The response, modified by this handler
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException if the controller doesn't support the current request type
     * @return void
     */
    public function processRequest(\TYPO3\CMS\Extbase\Mvc\RequestInterface $request, \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response)
    {
        $this->template = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');

        $GLOBALS['SOBE'] = new \stdClass();
        $GLOBALS['SOBE']->doc = $this->template;

        parent::processRequest($request, $response);

        $pageHeader = $this->template->startpage(
            $GLOBALS['LANG']->sL('LLL:EXT:workspaces/Resources/Private/Language/locallang.xml:module.title')
        );
        $pageEnd = $this->template->endPage();

        $response->setContent($pageHeader . $response->getContent() . $pageEnd);
    }


    /**
     * Wrapper for t3lib_PageRenderer->addJsFile. Excludes $jsFile from concatenation on TYPO3 4.6+.
     *
     * @param string $jsFile
     * @return void
     */
    protected function addJsFileToPageRenderer($jsFile)
    {
        if (version_compare(TYPO3_version, '4.6', '>=')) {
            $this->pageRenderer->addJsFile($jsFile, 'text/javascript', true, false, '', true);
        } else {
            $this->pageRenderer->addJsFile($jsFile);
        }
    }
}
