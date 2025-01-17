<?php
include '../../includes/sessions.php';
if(isset($_POST['logout'])){
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch.');
    }
    isloggedOut();
   

    die();
}