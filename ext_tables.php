<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

if (TYPO3_MODE === 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'AOE.' . $_EXTKEY,
        'tools',
        'schedulertimeline',
        'after:txschedulerM1',
        array(
            // An array holding the controller-action-combinations that are accessible
            'Timeline' => 'timeline'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/' . (\TYPO3\CMS\Core\Utility\GeneralUtility::compat_version('7.0') ? 'be_module_icon_v7.png' : 'moduleicon.png'),
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf'
        )
    );
}

/**
 * Table "tx_schedulertimeline_domain_model_log":
 */
$TCA['tx_schedulertimeline_domain_model_log'] = array(
    'ctrl' => array(
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'title' => 'Scheduler Timeline Log',
        'adminOnly' => 1,
        'rootLevel' => 1,
        'hideTable' => 1,
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . '/Configuration/TCA/tca.php',
    )
);

/**
 * Table "tx_scheduler_task":
 */
$TCA['tx_scheduler_task'] = array(
    'ctrl' => array(
        'label' => 'classname',
        'title' => 'Scheduler Task',
        'adminOnly' => 1,
        'rootLevel' => 1,
        'hideTable' => 1,
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . '/Configuration/TCA/tca.php',
    )
);
