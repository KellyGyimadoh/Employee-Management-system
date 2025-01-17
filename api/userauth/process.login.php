<?php
include '../../includes/sessions.php';
include '../../DatabaseModel/configdb/Dbconnection.php';
include '../../DatabaseModel/userdb/Login.php';
include '../../Controller/AuthController/LoginController.php';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

header('Content-Type: application/json');
try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('invalid json input');
    }
    $password = filter_var($input['password'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $csrfToken = $input['csrf_token'];
    if (!empty($csrfToken) &&  hash_equals($_SESSION['csrf_token'], $csrfToken)) {

        $user = new LoginController(
            $email,
            $password
        );
         
        $result = $user->verifyUser();
        if ($result['success'] && $result['success']==true) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        echo json_encode($result);
    } else {
      
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'invalid csrf token']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'messsage' => $e->getMessage()]);
}
exit;