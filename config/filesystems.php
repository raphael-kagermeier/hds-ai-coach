<?php

$s3BaseConfig = [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_PROJECT_DEFAULT_REGION'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
];

$localBaseConfig = [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL') . '/storage',
];

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'private'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'public' => array_merge(
            [
                'visibility' => 'public',
                'throw' => env('FILESYSTEM_DRIVER', 'local') === 's3',
            ],
            env('FILESYSTEM_DRIVER', 'local') === 's3'
                ? array_merge($s3BaseConfig, ['bucket' => env('PUBLIC_AWS_BUCKET')])
                : $localBaseConfig
        ),

        'private' => array_merge(
            [
                'visibility' => 'private',
                'throw' => env('FILESYSTEM_DRIVER', 'local') === 's3',
            ],
            env('FILESYSTEM_DRIVER', 'local') === 's3'
                ? array_merge($s3BaseConfig, ['bucket' => env('PRIVATE_AWS_BUCKET')])
                : [
                    'driver' => 'local',
                    'root' => storage_path('app/public'),
                ]
        ),

        'avatars' => array_merge(
            [
                'visibility' => 'private',
                'throw' => env('FILESYSTEM_DRIVER', 'local') === 's3',
            ],
            env('FILESYSTEM_DRIVER', 'local') === 's3'
                ? array_merge($s3BaseConfig, ['bucket' => env('PRIVATE_AWS_BUCKET'), 'root' => 'avatars'])
                : [
                    'driver' => 'local',
                    'root' => storage_path('app/public'),
                ]
        ),

        'robots' => [
            'driver' => 'local',
            'root' => resource_path('misc/robots'),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
