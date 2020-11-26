<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Scheduler Timeline',
    'description' => 'Logs information about scheduler task execution and displays them in a graphical timeline',
    'version' => '2.0.1',
    'category' => 'module',
    'author' => 'Fabrizio Branca, Erik Frister, Thomas Layh, Tomas Norre Mikkelsen, Stefan Rotsch, Nikola Stojiljković',
    'author_company' => 'AOE GmbH',
    'author_email' => 'dev@aoe.com',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => array(
        'depends' => array(
            'php' => '7.0.0-7.99.99',
            'typo3' => '7.6.0-7.99.99',
            'extbase' => '',
            'fluid' => '',
            'scheduler' => '',
        ),
        'conflicts' => array(),
        'suggests' => array(),
    )
);
