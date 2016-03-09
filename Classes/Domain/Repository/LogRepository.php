<?php

namespace AOE\SchedulerTimeline\Domain\Repository;

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

/**
 * Class LogRepository
 *
 * @package AOE\SchedulerTimeline\Domain\Repository
 */
class LogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var int min date timestamp
     */
    protected $minDate;

    /**
     * @var int max date timestamp
     */
    protected $maxDate;

    /**
     * Initialize object
     * Ignore storage pid
     *
     * @return void
     */
    public function initializeObject()
    {
        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Find logs by time
     *
     * @param int $starttime
     * @param int $endtime
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByTime($starttime, $endtime)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->logicalOr(
                    $query->greaterThanOrEqual('endtime', $starttime),
                    $query->equals('endtime', 0)
                ),
                $query->lessThanOrEqual('starttime', $endtime)
            )
        );
        $query->setOrderings(array('starttime' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
        return $query->execute();
    }

    /**
     * Find logs grouped by task
     *
     * @return array array(<taskUid> => array('task' => <task>, 'logs' => array(<log>, ...) ), ...)
     */
    public function findGroupedByTask()
    {
        $logs = $this->findAll();
        $result = array();
        foreach ($logs as $log) { /* @var $log \AOE\SchedulerTimeline\Domain\Model\Log */

            // min/max
            $startTime = $log->getStarttime();
            $this->minDate = is_null($this->minDate) ? $startTime : min($this->minDate, $startTime);
            $this->maxDate = is_null($this->maxDate) ? $startTime : max($this->maxDate, $startTime);

            $task = $log->getTask();
            $result[$task->getUid()]['task'] = $task;
            $result[$task->getUid()]['logs'][] = $log;
        }
        return $result;
    }

    /**
     * Get min date (from findGroupedByTask)
     *
     * @return int
     */
    public function getMinDate()
    {
        return $this->minDate;
    }

    /**
     * Get max date (from findGroupedByTask)
     *
     * @return int
     */
    public function getMaxDate()
    {
        return $this->maxDate;
    }
}
