<?php
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/ViewUser.php';
include '../../Controller/UserController/SelectUserProfile.php';
require '../../includes/sessions.php';
header('Content-Type: application/json');
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
    $accounttype=htmlspecialchars('admin');

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Fetch data using your class
    $viewUser = new SelectUserProfile($limit, $offset, $search,$accounttype);
    $users = $viewUser->getAllUsersDetails();
    $totalUsers = $viewUser->getUserCount();

    $response = [
        'users' => $users,
       'pagination' => [
            'current_page' => $page,
            'total_pages' => ceil($totalUsers / $limit),
            'total_users' => $totalUsers,
        ],
    ];

   
    echo json_encode($response);
    exit;
