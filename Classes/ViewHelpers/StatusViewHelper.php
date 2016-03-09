<?php

namespace AOE\SchedulerTimeline\ViewHelpers;

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
 * GanttViewHelper
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class StatusViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Render
	 *
	 * @param string $status
	 * @return string rendered tag
	 */
	public function render($status) {
		switch ($status) {
			case \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_SUCCESS:
				$result = '<span class="bar-green"><span>'.$status.'</span></span>';
				break;
			case \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_PENDING:
				$result = '<span class="bar-lightgray"><span>'.$status.'</span></span>';
				break;
			case \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_RUNNING:
				$result = '<span class="bar-yellow"><span>'.$status.'</span></span>';
				break;
			case \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_MISSED:
				$result = '<span class="bar-orange"><span>'.$status.'</span></span>';
				break;
			case \AOE\SchedulerTimeline\Domain\Model\Log::STATUS_ERROR:
				$result = '<span class="bar-red"><span>'.$status.'</span></span>';
				break;
			default:
				$result = $status;
				break;
		}
		return $result;
	}

}

