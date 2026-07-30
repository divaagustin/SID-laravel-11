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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ====================================================
    // BSrE (Balai Sertifikasi Elektronik) — TTE API
    // Daftar di: https://tte.bssn.go.id
    // ====================================================
    'bsre' => [
        'url'      => env('BSRE_URL', ''),
        'username' => env('BSRE_USERNAME', ''),
        'password' => env('BSRE_PASSWORD', ''),
        'timeout'  => env('BSRE_TIMEOUT', 30),
        'nik_ttd'  => env('BSRE_NIK_KEPALA_DESA', ''),
    ],

    // ====================================================
    // WhatsApp Notification Gateway API
    // Provider: fonnte | wablas | ruanggwa | custom
    // ====================================================
    'whatsapp' => [
        'provider' => env('WA_GATEWAY_PROVIDER', 'fonnte'),
        'url'      => env('WA_GATEWAY_URL', 'https://api.fonnte.com/send'),
        'token'    => env('WA_GATEWAY_TOKEN', ''),
        'timeout'  => env('WA_GATEWAY_TIMEOUT', 15),
    ],

    // ====================================================
    // OpenDK (Sistem Informasi Kecamatan) API Sync
    // ====================================================
    'opendk' => [
        'url'       => env('OPENDK_URL', ''),
        'api_key'   => env('OPENDK_API_KEY', ''),
        'kode_desa' => env('OPENDK_DESA_CODE', '12.09.18.2001'),
        'timeout'   => env('OPENDK_TIMEOUT', 30),
    ],

];
