<?php
require_once('../config.php');
$status = false;
$message = "";

$role = $_POST['role'];
$userId = $_POST['userId'];

if ($role !== "Reviewer" && $role !== "Editor") {
    $message = "Invalid Role Selection";
    $status = false;
} else {
    $stm = $pdo->prepare("UPDATE ejournal_users SET user_role=? WHERE user_id=?");
    $stm->execute([$role, $userId]);
    if ($role == "Reviewer") {
        $message = "Reviewer Assigned Succesfully";
    } else if ($role == "Editor") {
        $message = "Editor Assigned Succesfully";
    }
    $status = true;
}

// Response
$response = array(
    "status" => $status,
    "message" => $message,
);

echo json_encode($response);