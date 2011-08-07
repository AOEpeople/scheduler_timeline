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

require_once t3lib_extMgm::extPath('scheduler_timeline') . 'interface.tx_schedulertimeline_returnmessage.php';

/**
 * ExceptionTask
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class Tx_SchedulerTimeline_Scheduler_ExceptionTask extends tx_scheduler_Task implements tx_schedulertimeline_returnmessage {

	/**
	 * @var int
	 */
	protected $sleepTime;

	/**
	 * Execute
	 *
	 * @see tx_scheduler_Task::execute()
	 */
	public function execute() {
		$this->sleepTime = rand(0, 120);
		sleep($this->sleepTime);
		if (rand(0, 5) === 5) {
			throw new Exception('Dummy exception');
		}
		return true;
	}

	/**
	 * Return message
	 *
	 * @see tx_schedulertimeline_returnmessage::getReturnMessage()
	 */
	public function getReturnMessage() {
		return "Slept for {$this->sleepTime} seconds";
	}

	public function getAdditionalInformation() {
		return "Very long additional information for " . get_class($this);
	}

}