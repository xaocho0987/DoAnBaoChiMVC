<?php
include_once '../classes/user.php';
$user = new user();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminName = $_POST['adminName'];
    $Email = $_POST['Email'];
    $adminUser = $_POST['adminUser'];
    $adminPass = $_POST['adminPass'];
    $roleID = $_POST['roleID'];
    $Active = $_POST['Active'];
    $Avatar = $_POST['Avatar'];
    $insertUser = $user->insert_user($adminName, $Email, $adminUser, $adminPass, $roleID, $Active, $Avatar);
    http_response_code(200);
    die();
}
http_response_code(400);