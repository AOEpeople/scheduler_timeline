<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_scheduler']['className'] = 'Scheduler';
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Scheduler\Scheduler::class] = [
    'className' => \AOE\SchedulerTimeline\XClass\Scheduler::class
];

// Get the extensions's configuration
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['scheduler_timeline']);

if ((TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_AJAX) || ('BE' === TYPO3_MODE)) {
    /** @noinspection PhpUndefinedVariableInspection */
    $extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY);

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        file_get_contents($extPath . 'Configuration/TypoScript/Backend/setup.txt')
    );
}
