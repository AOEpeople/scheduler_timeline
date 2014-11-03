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
 * GanttViewHelper
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class Tx_SchedulerTimeline_ViewHelpers_GanttViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * Render
	 *
	 * @param Tx_SchedulerTimeline_Domain_Model_Log $log
	 * @param int $starttime
	 * @param int $zoom
	 * @return string rendered tag
	 */
	public function render(Tx_SchedulerTimeline_Domain_Model_Log $log, $starttime, $zoom) {

		$duration = $log->getDuration() / $zoom;
		$duration = ceil($duration / 4) * 4 - 1; // round to numbers dividable by 4, then remove 1 px border
		$duration = max($duration, 3);

		$offset = ($log->getStarttime() - $starttime) / $zoom;
		if ($offset < 0) { // cut bar
			$duration += $offset;
			$offset = 0;
		}
		$this->tag->addAttribute('style', sprintf('width: %spx; left: %spx;', $duration, $offset));

		$this->tag->addAttribute('class', 'task ' . $log->getStatus());
		$this->tag->addAttribute('id', 'uid_'.$log->getUid());
		$this->tag->setContent($this->renderChildren());
		return $this->tag->render();
	}

}

