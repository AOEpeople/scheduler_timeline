<?php

if (!defined ('TYPO3_MODE')) die ('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['BE']['XCLASS']['ext/scheduler/class.tx_scheduler.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_tx_scheduler.php';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_scheduler']['className'] = 'ux_tx_scheduler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Scheduler\\Scheduler']['className'] = 'ux_tx_scheduler';

	// Get the extensions's configuration
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['scheduler_timeline']);

	// If sample tasks should be shown,
	// register information for the test and sleep tasks
if (!empty($extConf['showSampleTasks'])) {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_SchedulerTimeline_Scheduler_ExceptionTask'] = array(
		'extension'        => $_EXTKEY,
		'title'            => 'Exception test',
		'description'      => 'Task to test exceptions',
	);
}

if ((TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_AJAX) || ('BE' === TYPO3_MODE)) {
	/** @noinspection PhpUndefinedVariableInspection */
	$extPath = t3lib_extMgm::extPath($_EXTKEY);

	/*t3lib_extMgm::addTypoScriptConstants(
		file_get_contents($extPath . 'Configuration/TypoScript/Backend/constants.txt')
	);*/

	t3lib_extMgm::addTypoScriptSetup(
		file_get_contents($extPath . 'Configuration/TypoScript/Backend/setup.txt')
	);
}

?>