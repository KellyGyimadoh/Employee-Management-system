<?php
include_once 'functions.php';
ini_set('session.use_only_cookies',1);
ini_set('session.use_strict_mode',1);
session_set_cookie_params([
'lifetime'=>1800,
'domain'=>'localhost',
'path'=>'/',
'secure'=>true,
'httponly'=>true
]);
session_start();
date_default_timezone_set('Africa/Accra');
if(isset($_SESSION['userid'])){
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_session_id_loggedin();
    }else{
        $interval=60*50;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_session_id_loggedin();

        }
    }
}else{
    if(!isset($_SESSION['last_regeneration'])){
        regenerate_session_id();

    }else{
        $interval=60*50;
        if(time()-$_SESSION['last_regeneration']>=$interval){
            regenerate_session_id();
        }
    }
}
if(!isset($_SESSION['csrf_token'])){
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
}else{
    $interval=60*50;
    if(time()-$_SESSION['csrf_token']>=$interval){
        $_SESSION['csrf_token']=bin2hex(random_bytes(32));

    }
}