<?php

/**
 * ===========================================
 * EventEase - Global Configuration
 * ===========================================
 */


/*
|--------------------------------------------------------------------------
| Session Security
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {

    /*
    |----------------------------------------------------------------------
    | Secure session cookie settings
    |----------------------------------------------------------------------
    |
    | HttpOnly:
    | JavaScript cannot directly access the session cookie.
    |
    | SameSite=Lax:
    | Helps reduce unwanted cross-site requests while keeping
    | normal navigation/login flows working.
    |
    */

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}


/*
|--------------------------------------------------------------------------
| Timezone
|--------------------------------------------------------------------------
*/

date_default_timezone_set('Asia/Kolkata');


/*
|--------------------------------------------------------------------------
| Project Information
|--------------------------------------------------------------------------
*/

define('SITE_NAME', 'EventEase');

define(
    'SITE_URL',
    'http://localhost/EventEase'
);


/*
|--------------------------------------------------------------------------
| Upload Directory
|--------------------------------------------------------------------------
*/

define(
    'UPLOAD_PATH',
    __DIR__ . '/../assets/uploads/'
);


/*
|--------------------------------------------------------------------------
| Default Profile Image
|--------------------------------------------------------------------------
*/

define(
    'DEFAULT_PROFILE_IMAGE',
    'default.png'
);


/*
|--------------------------------------------------------------------------
| Error Reporting
|--------------------------------------------------------------------------
|
| Errors are logged instead of being displayed to users.
|
*/

error_reporting(E_ALL);

ini_set('display_errors', '0');

ini_set('log_errors', '1');

?>