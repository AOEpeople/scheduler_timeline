<?php

require_once t3lib_extMgm::extPath('scheduler_timeline') . 'interface.tx_schedulertimeline_returnmessage.php';

class Tx_SchedulerTimeline_Scheduler_ExceptionTask extends tx_scheduler_Task implements tx_schedulertimeline_returnmessage {

	protected $sleepTime;

	public function execute() {
		sleep(300);
		return true;
		$this->sleepTime = rand(0, 120);
		sleep($this->sleepTime);
		if (rand(0, 5) === 5) {
			throw new Exception('Dummy exception');
		}
		return true;
	}

	public function getReturnMessage() {
		return "Slept for {$this->sleepTime} seconds";
	}

}