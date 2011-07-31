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
 * DurationViewHelper
 *
 * @author	Fabrizio Branca <typo3@fabrizio-branca.de>
 * @package TYPO3
 * @subpackage tx_schedulertimeline
 */
class Tx_SchedulerTimeline_ViewHelpers_DurationViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Render
	 *
	 * @param int $duration
	 * @return string rendered tag
	 */
	public function render($duration) {

		$hours = intval(intval($duration) / 3600);
		$hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
		$minutes = intval(($duration / 60) % 60);
		$seconds = intval($duration % 60);

		$result = $seconds;
		if (intval($minutes) || intval($hours)) {
			$seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
			$result = $minutes .':'.$seconds;
		}
		if (intval($hours)) {
			$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
			$result = $hours .':'.$minutes .':'.$seconds;
		}
		return $result;
	}

}

