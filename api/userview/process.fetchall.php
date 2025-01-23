<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/ViewUser.php';
include '../../Controller/UserController/SelectUserProfile.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
 $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    $account_type = htmlspecialchars("staff");

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Fetch data using your class
    $viewUser = new SelectUserProfile($limit, $offset, $search, $account_type);
    $users = $viewUser->getAllUsers();
    $totalUsers = $viewUser->getUserCount();

    $response = [
        'users' => $users,
       
    ];

   
    echo json_encode($response);
    exit;
