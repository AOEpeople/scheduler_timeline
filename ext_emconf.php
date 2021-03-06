<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Scheduler Timeline',
    'description' => 'Logs information about scheduler task execution and displays them in a graphical timeline',
    'version' => '8.1.1',
    'category' => 'module',
    'author' => 'Fabrizio Branca, Erik Frister, Thomas Layh, Tomas Norre Mikkelsen, Stefan Rotsch, Nikola Stojiljković',
    'author_company' => 'AOE GmbH',
    'author_email' => 'dev@aoe.com',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'constraints' => [
        'depends' => [
            'php' => '7.0.0-7.2.99',
            'typo3' => '8.7.0-8.7.99',
            'extbase' => '',
            'fluid' => '',
            'scheduler' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
