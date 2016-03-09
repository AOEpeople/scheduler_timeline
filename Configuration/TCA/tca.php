<?php
/**
 * System workspaces - Defines the offline workspaces available to users in TYPO3.
 */
$TCA['tx_schedulertimeline_domain_model_log'] = array(
	'ctrl' => $TCA['tx_schedulertimeline_domain_model_log']['ctrl'],
	'columns' => array(
		'task' => array(
			'label' => 'Task',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),
		'starttime' => array(
			'label' => 'starttime',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),
		'endtime' => array(
			'label' => 'endtime',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),
		'exception' => array(
			'label' => 'exception',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),
		'returnmessage' => array(
			'label' => 'returnmessage',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),
		'processid' => array(
			'label' => 'processid',
			'config' => array(
				'type' => 'input',
				'size' => '20',
				'max' => '30',
			)
		),


	),
	'types' => array(
		'0' => array('showitem' => 'task, starttime, endtime, exception, returnmessage, processid')
	)
);

$TCA['tx_scheduler_task'] = array(
	'ctrl' => $TCA['tx_scheduler_task']['ctrl'],
	'columns' => array(
		'classname' => array(
			'label' => 'Classname',
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
		),
	),
	'types' => array(
		'0' => array('showitem' => 'classname')
	)
);