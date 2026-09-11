<?php
session_start();
require_once(__DIR__ . '/../model/adminModel.php');

if (isset($_POST['action']) && $_POST['action'] === 'add_room') {
    $room_name = trim($_POST['room_name']);
    $capacity = intval($_POST['capacity']);
    $supervisor_id = intval($_POST['supervisor_id']);

    if (!empty($room_name) && $capacity > 0) {
        createRoom($room_name, $capacity, $supervisor_id);
    }
    header("Location: ../view/admin_dashboard.php");
    exit();
}

if (isset($_POST['action']) && $_POST['action'] === 'update_role') {
    $user_id = intval($_POST['user_id']);
    $role = trim($_POST['role']);

    if ($user_id > 0 && !empty($role)) {
        updateUserRole($user_id, $role);
    }
    header("Location: ../view/admin_dashboard.php");
    exit();
}
?>