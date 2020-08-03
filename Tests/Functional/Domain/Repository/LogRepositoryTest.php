<?php

namespace AOE\SchedulerTimeline\Tests\Functional\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

/**
 * Class LogRepositoryTest
 *
 * @package AOE\SchedulerTimeline\Tests\Functional\Domain\Repository
 */
class LogRepositoryTest extends FunctionalTestCase
{

    /**
     * @var \AOE\SchedulerTimeline\Domain\Repository\LogRepository
     */
    protected $logRepository;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var array
     */
    protected $coreExtensionsToLoad = array('scheduler');

    /**
     * @var array
     */
    protected $testExtensionsToLoad = array('typo3conf/ext/scheduler_timeline');

    /**
     * SetUp
     */
    public function setUp()
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/Fixtures/tx_schedulertimeline_domain_model_log.xml');
        $this->importDataSet(__DIR__ . '/Fixtures/tx_scheduler_task.xml');
        $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->logRepository = $this->objectManager->get('AOE\\SchedulerTimeline\\Domain\\Repository\\LogRepository');
    }

    /**
     * Tears down the fixture
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function findByTimeReturnsQueryResultInterface()
    {
        // Order by starttime
        $expectedArray = array(5,1,2);

        $startTime = 1445191476; // 18.10.15 18:04
        $endTime = 1445191876; // 18.10.15 18:11
        $logs = $this->logRepository->findByTime($startTime, $endTime);

        /** @var \AOE\SchedulerTimeline\Domain\Model\Log $log */
        foreach ($logs as $log) {
            $actualArray[] = $log->getUid();
        }

        $this->assertSame(
            $expectedArray,
            $actualArray
        );
    }

    /**
     * @test
     */
    public function findGroupedByTaskReturnsMultidimensionArray()
    {
        $actualArray = array();
        $expectedArray = array(
            131 => array(
                'task' => 131,
                'logs' => array(1)
            ),
            132 => array(
                'task' => 132,
                'logs' => array(2)
            ),
            133 => array(
                'task' => 133,
                'logs' => array(3)
            ),
            134 => array(
                'task' => 134,
                'logs' => array(4,5)
            ),
        );

        $startTime = 1445191476; // 18.10.15 18:04
        $endTime = 1445191876; // 18.10.15 18:11
        $logsGroupedByTask = $this->logRepository->findGroupedByTask($startTime, $endTime);

        foreach ($logsGroupedByTask as $taskUid => $taskObject) {
            $actualArray[$taskUid]['task'] = $taskUid;

            /** @var \AOE\SchedulerTimeline\Domain\Model\Task $task */
            foreach ($taskObject as $task) {
                /** @var \AOE\SchedulerTimeline\Domain\Model\Log $log */
                foreach ($task as $log) {
                    $actualArray[$taskUid]['logs'][] = $log->getUid();
                }
            }
        }

        $this->assertSame(
            sort($expectedArray),
            sort($actualArray)
        );
    }
}
