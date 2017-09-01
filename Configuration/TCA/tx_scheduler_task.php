<?php
defined('TYPO3_MODE') or die();

// Ensure $TCA for tx_scheduler_task is initialized
// Workaround for TYPO3 6.2 not providing a $TCA for that particular table
if (!isset($GLOBALS['TCA']['tx_scheduler_task'])) {
    return array(
        'ctrl' => array(
            'label' => 'classname',
            'title' => 'LLL:EXT:scheduler_timeline/Resources/Private/Language/locallang_tca.xlf:tx_scheduler_task',
            'adminOnly' => 1,
            'rootLevel' => 1,
            'hideTable' => 1,
        ),
        'columns' => array(),
        'types' => array()
    );
}
