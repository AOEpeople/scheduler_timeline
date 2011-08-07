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
 * Log
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class Tx_SchedulerTimeline_Domain_Model_Log extends Tx_Extbase_DomainObject_AbstractEntity {

	const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_SUCCESS = 'success';
    const STATUS_MISSED = 'missed';
    const STATUS_ERROR = 'error';

	/**
	 * @var Tx_SchedulerTimeline_Domain_Model_Task
	 */
	protected $task;

	/**
	 * @var int
	 */
	protected $starttime;

	/**
	 * @var int
	 */
	protected $endtime;

	/**
	 * @var string
	 */
	protected $exception;

	/**
	 * @var string
	 */
	protected $returnmessage;

	/**
	 * @var int
	 */
	protected $processid;

	/**
	 * Get task
	 *
	 * @return Tx_SchedulerTimeline_Domain_Model_Task
	 */
	public function getTask() {
		return $this->task;
	}

	/**
	 * @return the $starttime
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * @return the $endtime
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * @return the $exception
	 */
	public function getException() {
		return unserialize($this->exception);
	}

	/**
	 * @return the $returnmessage
	 */
	public function getReturnmessage() {
		return $this->returnmessage;
	}

	/**
	 * Get the process id
	 *
	 * @return int
	 */
	public function getProcessid() {
		return $this->processid;
	}

	/**
	 * @return int duration (in sec)
	 */
	public function getDuration() {
		$endtime = $this->getEndtime() ? $this->getEndtime() : time();
		return $endtime - $this->getStarttime();
	}

	/**
	 * Get formatted timespan
	 *
	 * @return string
	 */
	public function getTimespan() {
		$timespan = date('H:i',  $this->getStarttime());
		if ($this->isRunning()) {
			$timespan .= ' - (still running)';
		} else {
			$timespan .= ' - ' . date('H:i',  $this->getEndtime());
		}
		return $timespan;
	}

	/**
	 * Is this task running?
	 *
	 * @return bool
	 */
	public function isRunning() {
		return (!$this->getEndtime());
	}

	/**
	 * Get status
	 *
	 * @return string see class constants STATUS_*
	 */
	public function getStatus() {
		if ($this->getException()) {
			return self::STATUS_ERROR;
		} elseif ($this->isRunning()) {
			return self::STATUS_RUNNING;
		} else {
			return self::STATUS_SUCCESS;
		}
	}

}