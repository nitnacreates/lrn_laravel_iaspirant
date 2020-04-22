<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'subject_map' => [
        'csat' => 'CSAT',
        'economics' => 'Economics',
        'ethics' => 'Ethics',
        'polity' => 'Politics',
        'geography' => 'Geography',
        'history' => 'History',
        'international_relations' => 'International Relations',
        'science_and_technology' => 'Science And Technology',
        'society' => 'Society'
    ],

    'essay_word_limits' => [50,100,150,200,250,300,500,1000],

    'news' => [
        'remove_lock_at' => '17:00:00'
    ],

    'media' => [
        'super' => '/public/mlib/',
        'users' => '/public/aspirant/'
    ]
];
