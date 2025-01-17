<?php
require 'functions.php';
ini_set('session.use_only_cookies',1);
ini_set('session.use_strict_mode',1);
// header('X-Frame-Options: SAMEORIGIN');
// header('X-Content-Type-Options: nosniff');
// header('X-XSS-Protection: 1; mode=block');
// Use HTTPS only if available
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

session_set_cookie_params([
'lifetime'=>1800,
'domain'=>'employee.test',
'path'=>'/',
'secure'=>false,
'httponly'=>true
]);
session_start();

date_default_timezone_set('Africa/Accra');
if(isset($_SESSION['userid'])){
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_session_id_loggedin();
    }else{
        $interval=60*30;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_session_id_loggedin();

        }
    }
}else{
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_session_id();

    }else{
        $interval=60*30;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_session_id();
        }
    }
}
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    $_SESSION['csrf_token_time'] = time();
}else{
    $interval=60*30;
    if(time()-$_SESSION['csrf_token_time']>=$interval){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();

    }
}?>
