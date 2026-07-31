<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing them in a temporary directory
    | before they are saved to their final destination.
    |
    */

    'temporary_file_upload' => [
        'disk' => null,
        'rules' => ['file', 'max:51200'], // 50MB Max Upload Size
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a', 'jpg',
            'jpeg', 'mpga', 'webp', 'wma', 'pdf',
        ],
        'max_upload_time' => 10,
    ],

];
