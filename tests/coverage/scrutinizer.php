<?php

use GuzzleHttp\Client;

require_once __DIR__ . '/../../vendor/autoload.php';

(new Client())->post(
    'https://scrutinizer-ci.com/api/repositories/g/staudenmeir/eloquent-eager-limit/data/code-coverage',
    [
        'json' => [
            'revision' => exec('git rev-parse HEAD'),
            'parents' => [
                0 => exec('git log --pretty=%P -n1 HEAD'),
            ],
            'coverage' => [
                'format' => 'php-clover',
                'data' => base64_encode(
                    str_replace(
                        getcwd() . '/',
                        '{scrutinizer_project_base_path}/',
                        file_get_contents('coverage.xml')
                    )
                ),
            ],
        ],
    ]
);
