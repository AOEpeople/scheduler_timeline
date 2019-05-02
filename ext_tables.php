<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE' && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'AOE.' . $_EXTKEY,
        'tools',
        'schedulertimeline',
        'after:txschedulerM1',
        [
            // An array holding the controller-action-combinations that are accessible
            'Timeline' => 'timeline',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Images/be_module_icon_v7.png',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf',
        ]
    );
}
