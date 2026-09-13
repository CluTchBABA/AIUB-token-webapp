<?php
session_start();
include_once "../model/Users.php";

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'teacher') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['update_profile'])) {
    header("Location: ../view/update_profile.php");
    exit();
}

$id = (int)$_SESSION['id'];
$fullname = trim($_POST['fullname'] ?? '');
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';

if ($fullname === '' || $password === '' || $cpassword === '') {
    $_SESSION['error_message'] = "All fields are required.";
    header("Location: ../view/teacher_update_profile.php");
    exit();
}
if ($password !== $cpassword) {
    $_SESSION['error_message'] = "Passwords do not match.";
    header("Location: ../view/teacher_update_profile.php");
    exit();
}

$user_model = new Users();
$row = $user_model->update_user($id, $fullname, $password);

if ($row) {
    $_SESSION['name'] = $fullname;
    $_SESSION['status_message'] = "Profile updated successfully.";
    header("Location: ../view/teacher_view_profile.php");
} else {
    $_SESSION['error_message'] = "Failed to update profile. Please try again.";
    header("Location: ../view/teacher_update_profile.php");
}
exit();