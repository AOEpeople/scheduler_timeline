<?php

namespace AOE\SchedulerTimeline\XClass;

/***************************************************************
*  Copyright notice
*
*  (c) 2011 Fabrizio Branca <typo3@fabrizio-branca.de>
*
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
 * Class Scheduler
 * @package AOE\SchedulerTimeline\XClass
 */
class Scheduler extends \TYPO3\CMS\Scheduler\Scheduler {

    /**
     * Wraps the executeTask method
     *
     * @param \TYPO3\CMS\Scheduler\Task\AbstractTask $task The task to execute
     * @return boolean Whether the task was saved successfully to the database or not
	 * @throws \Exception
     */
    public function executeTask(\TYPO3\CMS\Scheduler\Task\AbstractTask $task) {

        $taskUid = $task->getTaskUid();

        // log start
        $logUid = $this->logStart($taskUid);

        $failure = NULL;
        try {
            $result = parent::executeTask($task);
        } catch(\Exception $e) {
            $failure = $e;
        }

        if ($result || $failure) {
            $returnMessage = '';
            if ($task instanceof \AOE\SchedulerTimeline\Interfaces\ReturnMessage || is_callable(array($task, 'getReturnMessage'))) {
                $returnMessage = $task->getReturnMessage();
            }

            // log end
            $this->logEnd($logUid, $failure, $returnMessage);
        } else {
            // task was not executed, because another task was running
            // and multiple execution is not allowed for this task
            $this->removeLog($logUid);
        }

        if ($failure instanceof \Exception) {
            throw $failure;
        }

        return $result;
    }

    /**
     * Extend the method to cleanup up the log table aswell
     *
     * @see tx_scheduler::cleanExecutionArrays()
     */
    protected function cleanExecutionArrays() {
        parent::cleanExecutionArrays();
        $this->cleanupLog();
    }

    /**
     * Cleanup log
     *
     * @return void
     * @throws \Exception
     */
    protected function cleanupLog() {

    	$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['scheduler_timeline']);

        // clean old log entries
        $dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj \TYPO3\CMS\Core\Database\DatabaseConnection */
        $res = $dbObj->exec_DELETEquery('tx_schedulertimeline_domain_model_log', 'endtime > 0 AND endtime <'.(time()- $extConf['cleanLogEntriesOlderThan'] * 60));
        if ($res === false) {
            throw new \Exception('Error while cleaning log');
        }

        // clean tasks, that exceeded the maxLifetime
        $maxDuration = $this->extConf['maxLifetime'] * 60;
        $dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj \TYPO3\CMS\Core\Database\DatabaseConnection */
        $res = $dbObj->exec_UPDATEquery(
            'tx_schedulertimeline_domain_model_log',
            'endtime = 0 AND starttime < ' . (time() - $maxDuration),
            array(
                'endtime' => time(),
                'exception' => serialize(array('message' => 'Task was cleaned up, because it exceeded maxLifetime.'))
            )
        );
        if ($res === false) {
            throw new \Exception('Error while cleaning tasks');
        }

        // check if process are still alive that have been started more than x minutes ago
        $checkProcessesAfter = intval($extConf['checkProcessesAfter']) * 60;
        if ($checkProcessesAfter) {
	        $res = $dbObj->exec_SELECTquery('uid, processid, task', 'tx_schedulertimeline_domain_model_log', 'endtime = 0 AND starttime < ' . (time() - $checkProcessesAfter));
	        if (is_resource($res)) {
		        while (($row = $dbObj->sql_fetch_assoc($res)) !== false) {
		            $processId = $row['processid'];
		            if (!$this->checkProcess($processId)) {

		            	// update log
		            	$res2 = $dbObj->exec_UPDATEquery(
		                    'tx_schedulertimeline_domain_model_log',
		                    'uid = '.intval($row['uid']),
		                    array(
		                        'endtime' => time(),
		                        'exception' => serialize(array('message' => 'Task was cleaned up, because it seems to be dead.'))
		                    )
		                );
		                if ($res2 === false) { throw new \Exception('Error while cleaning tasks'); }

		                $exception = new \TYPO3\CMS\Scheduler\FailedExecutionException('Task was cleaned up, because it seems to be dead.');

		                // update scheduler task
						$res3 = $dbObj->exec_UPDATEquery(
		                    'tx_scheduler_task',
		                    'uid = '.intval($row['task']),
		                    array(
		                        'serialized_executions' => '',
		                        'lastexecution_failure' => serialize($exception)
		                    )
		                );

		            	if ($res3 === false) { throw new \Exception('Error while cleaning tasks'); }

		            }
		        }
	        }
        }

    }

    /**
     * Log the start of a task
     *
     * @param int $taskUid
     * @throws \Exception
     * @return int
     */
    protected function logStart($taskUid) {
        $now = time();
        $dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj \TYPO3\CMS\Core\Database\DatabaseConnection */
        $res = $dbObj->exec_INSERTquery('tx_schedulertimeline_domain_model_log', array(
            'pid' => '0',
            'tstamp' => $now,
            'starttime' => $now,
            'task' => $taskUid,
            'processid' => getmypid()
        ));
        if ($res === false) {
            throw new \Exception('Error while inserting log entry');
        }
        return $dbObj->sql_insert_id();
    }

    /**
     * Remove a log entry
     *
     * @param int $logUid
     * @throws \Exception
     * @return void
     */
    protected function removeLog($logUid) {
        $dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj \TYPO3\CMS\Core\Database\DatabaseConnection */
        $res = $dbObj->exec_DELETEquery('tx_schedulertimeline_domain_model_log', 'uid='.intval($logUid));
        if ($res === false) {
            throw new \Exception('Error while deleting log entry');
        }
    }

    /**
     * Log the end of a task
     *
     * @param int $logUid
     * @param \Exception $failure
     * @param string $returnMessage
     * @throws \Exception
     */
    protected function logEnd($logUid, $failure, $returnMessage) {
        $exception = '';
        if ($failure instanceof \Exception) { /* @var $failure \Exception */
            $exception = serialize(array(
                'message' => $failure->getMessage(),
                'stacktrace' => $failure->getTraceAsString(),
                'endtime' => time(),
                'class' => get_class($failure)
            ));
        }

        $dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj \TYPO3\CMS\Core\Database\DatabaseConnection */
        $res = $dbObj->exec_UPDATEquery('tx_schedulertimeline_domain_model_log', 'uid='.intval($logUid), array(
            'endtime' => time(),
            'exception' => $exception,
            'returnmessage' => $returnMessage
        ));
        if ($res === false) {
            throw new \Exception('Error while updating log entry');
        }
    }

    /**
     * Check process
     *
     * @param int $pid
     * @return bool
     */
    protected function checkProcess($pid) {
        // form the filename to search for
        $file = '/proc/' . (int) $pid . '/cmdline';
        $fp = false;
        if (file_exists($file)) {
            $fp = @fopen($file, 'r');
        }

        if (!$fp) { // if file does not exist or cannot be opened, return false
            return false;
        }
        $buf = fgets($fp);
        fclose($fp);

        if ($buf === false) { // if we failed to read from file, return false
            return false;
        }
        return true;
    }

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler_timeline/Classes/XClass/Scheduler.php'])) {
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler_timeline/Classes/XClass/Scheduler.php']);
}