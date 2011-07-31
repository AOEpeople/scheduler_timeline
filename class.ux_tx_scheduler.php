<?php
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

class ux_tx_scheduler extends tx_scheduler {

	/**
     * Wraps the executeTask method
	 *
	 * @param tx_scheduler_Task $task The task to execute
	 * @return boolean Whether the task was saved successfully to the database or not
	 */
	public function executeTask(tx_scheduler_Task $task) {

		$taskUid = $task->getTaskUid();

		// log start
		$logUid = $this->logStart($taskUid);

		$failure = NULL;
		try {
			$result = parent::executeTask($task);
		} catch(Exception $e) {
			$failure = $e;
		}

		if ($result || $failure) {
			$returnMessage = '';
			if ($task instanceof tx_schedulertimeline_returnmessage) {
				$returnMessage = $task->getReturnMessage();
			}

			// log end
			$this->logEnd($logUid, $failure, $returnMessage);
		} else {
			// task was not executed, because another task was running
			// and multiple execution is not allowed for this task
			$this->removeLog($logUid);
		}

		if ($failure instanceof Exception) {
			throw $failure;
		}

		return $result;
	}

	protected function cleanExecutionArrays() {
		parent::cleanExecutionArrays();
		$this->cleanupLog();
		$this->cleanupTasks();
	}

	protected function cleanupTasks() {
		$maxDuration = $this->extConf['maxLifetime'] * 60;
		$dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj t3lib_db */
		$res = $dbObj->exec_UPDATEquery(
			'tx_schedulertimeline_domain_model_log',
			'endtime = 0 AND starttime < ' . (time() - $maxDuration),
			array(
				'endtime' => time(),
				'exception' => serialize(array('message' => 'Task was cleaned up, because it exceeded maxLifetime.'))
			)
		);
		if ($res === false) {
			throw new Exception('Error while cleaning tasks');
		}
	}

	protected function cleanupLog() {
		$dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj t3lib_db */
		$res = $dbObj->exec_DELETEquery('tx_schedulertimeline_domain_model_log', 'endtime > 0 AND endtime <'.(time()-24*60*60));
		if ($res === false) {
			throw new Exception('Error while cleaning log');
		}
	}

	protected function logStart($taskUid) {
		$now = time();
		$dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj t3lib_db */
		$res = $dbObj->exec_INSERTquery('tx_schedulertimeline_domain_model_log', array(
			'pid' => '0',
			'tstamp' => $now,
			'starttime' => $now,
			'task' => $taskUid,
			'processid' => getmypid()
		));
		if ($res === false) {
			throw new Exception('Error while inserting log entry');
		}
		return $dbObj->sql_insert_id();
	}

	protected function removeLog($logUid) {
		$dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj t3lib_db */
		$res = $dbObj->exec_DELETEquery('tx_schedulertimeline_domain_model_log', 'uid='.intval($logUid));
		if ($res === false) {
			throw new Exception('Error while deleting log entry');
		}
	}

	protected function logEnd($logUid, $failure, $returnMessage) {
		$exception = '';
		if ($failure instanceof Exception) { /* @var $failure Exception */
			$exception = serialize(array(
				'message' => $failure->getMessage(),
				'stacktrace' => $failure->getTraceAsString(),
				'endtime' => time(),
				'class' => get_class($failure)
			));
		}

		$dbObj = $GLOBALS['TYPO3_DB']; /* @var $dbObj t3lib_db */
		$res = $dbObj->exec_UPDATEquery('tx_schedulertimeline_domain_model_log', 'uid='.intval($logUid), array(
			'endtime' => time(),
			'exception' => $exception,
			'returnmessage' => $returnMessage
		));
		if ($res === false) {
			throw new Exception('Error while updating log entry');
		}
	}

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler_timeline/class.ux_tx_scheduler.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/scheduler_timeline/class.ux_tx_scheduler.php']);
}


?>