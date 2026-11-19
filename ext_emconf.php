<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'T3Masonry',
    'description' => 'T3Masonry: five image gallery layouts, CSS only (classic masonry, captions below, justified rows, spotlight, essential) for the content elements image, textpic, media and textmedia, with the bootstrap_package lightbox. Requires bootstrap_package (Bootstrap 5).',
    'category' => 'fe',
    'version' => '1.0.0',
    'state' => 'stable',
    'author' => 'Giulia Tuveri',
    'author_email' => 'gt@specialistaweb.it',
    'author_company' => 'Specialistaweb',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.5.99',
            'typo3' => '12.4.0-14.4.99',
            'bootstrap_package' => '14.0.0-16.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'SpecialistaWeb\\T3Masonry\\' => 'Classes/',
        ],
    ],
];
