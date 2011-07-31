<?php

class Tx_SchedulerTimeline_Domain_Model_Log extends Tx_Extbase_DomainObject_AbstractEntity {

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

	public function getTimespan() {
		$timespan = date('H:i',  $this->getStarttime());
		if ($this->isRunning()) {
			$timespan .= ' - (still running)';
		} else {
			$timespan .= ' - ' . date('H:i',  $this->getEndtime());
		}
		return $timespan;
	}

	public function isRunning() {
		return (!$this->getEndtime());
	}





}