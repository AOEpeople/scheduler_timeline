<?php
defined('TYPO3_MODE') or die();

$additionalColumns = array(
    'classname' => array(
        'label' => 'classname',
        'config' => array(
            'type' => 'input',
            'size' => '20',
            'max' => '30',
        )
    ),
    'serialized_task_object' => array(
        'label' => 'serialized_task_object',
        'config' => array(
            'type' => 'input',
            'size' => '20',
            'max' => '30',
        )
    )
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tx_scheduler_task',
    $additionalColumns
);
