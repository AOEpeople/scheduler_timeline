<?php

return array(
    'ctrl' => array(
        'label' => 'uid',
        'tstamp' => 'tstamp',
        'title' => 'LLL:EXT:scheduler_timeline/Resources/Private/Language/locallang_tca.xlf:tx_schedulertimeline_domain_model_log',
        'adminOnly' => 1,
        'rootLevel' => 1,
        'hideTable' => 1,
    ),
    'columns' => array(
        'task' => array(
            'label' => 'task',
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
