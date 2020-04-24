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
        'ir' => 'International Relations',
        'science-tech' => 'Science And Technology',
        'society' => 'Society'
    ],

    'optional_map' => [
        'o-agriculture' => 'OP Agriculture',
        'o-animal' => 'OP Animal Husbandry and Veterinary Science',
        'o-anthropology' => 'OP Anthropology',
        'o-botany' => 'OP Botany',
        'o-chemistry' => 'OP Chemistry',
        'o-civil' => 'OP Civil Engineering',
        'o-commerce-account' => 'OP Commerce and Accountancy',
        'o-economics' => 'OP Economics',
        'o-electrical Engineering' => 'OP Electrical Engineering',
        'o-geography' => 'OP Geography',
        'o-geology' => 'OP Geology',
        'o-history' => 'OP History',
        'o-law' => 'OP Law',
        'o-management' => 'OP Management',
        'o-math' => 'OP Mathematics',
        'o-mechanical' => 'OP Mechanical Engineering',
        'o-medical' => 'OP Medical Science',
        'o-philosophy' => 'OP Philosophy',
        'o-physics' => 'OP Physics',
        'o-political-ir' => 'OP Political Science and International Relations',
        'o-psychology' => 'OP Psychology',
        'o-public-admin' => 'OP Public Administration',
        'o-sociology' => 'OP Sociology',
        'o-statistics' => 'OP Statistics',
        'o-zoology' => 'OP Zoology',
        'o-litr-assamese' => 'Language - Assamese',
        'o-litr-bengali' => 'Language - Bengali',
        'o-litr-bodo' => 'Language - Bodo',
        'o-litr-dogri' => 'Language - Dogri',
        'o-litr-english' => 'Language - English',
        'o-litr-gujarati' => 'Language - Gujarati',
        'o-litr-hindi' => 'Language - Hindi',
        'o-litr-kannada' => 'Language - Kannada',
        'o-litr-kashmiri' => 'Language - Kashmiri',
        'o-litr-konkani' => 'Language - Konkani',
        'o-litr-maithili' => 'Language - Maithili',
        'o-litr-malayalam' => 'Language - Malayalam',
        'o-litr-manipuri' => 'Language - Manipuri',
        'o-litr-marathi' => 'Language - Marathi',
        'o-litr-nepali' => 'Language - Nepali',
        'o-litr-odia' => 'Language - Odia',
        'o-litr-punjabi' => 'Language - Punjabi',
        'o-litr-sanskrit' => 'Language - Sanskrit',
        'o-litr-santhali' => 'Language - Santhali',
        'o-litr-sindhi' => 'Language - Sindhi',
        'o-litr-tamil' => 'Language - Tamil',
        'o-litr-telugu' => 'Language - Telugu',
        'o-litr-urdu' => 'Language - Urdu'
    ],

    'essay_word_limits' => [50,100,150,200,250,300,500,1000],

    'news' => [
        'remove_lock_at' => '17:00:00',
        'type' => [
            'dn' => 'Daily News',
            'ed' => 'Editorial'
        ]
    ],

    'media' => [
        'super' => '/public/mlib/',
        'users' => '/public/aspirant/'
    ]
];
