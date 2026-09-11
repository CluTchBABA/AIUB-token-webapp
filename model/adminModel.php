<?php
require_once(__DIR__ . '/../db/db_connection.php');

function getAllUsers() {
    $conn = getConnection();
    $result = mysqli_query($conn, "SELECT * FROM user");
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    mysqli_close($conn);
    return $users;
}

function getAllSupervisors() {
    $conn = getConnection();
    $result = mysqli_query($conn, "SELECT user_id, fullname FROM user WHERE role = 'Supervisor'");
    $supervisors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $supervisors[] = $row;
    }
    mysqli_close($conn);
    return $supervisors;
}

function createRoom($name, $capacity, $supervisor_id) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "INSERT INTO room (name, capacity, supervisor_id) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sii", $name, $capacity, $supervisor_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    return $success;
}

function updateUserRole($user_id, $role) {
    $conn = getConnection();
    $stmt = mysqli_prepare($conn, "UPDATE user SET role = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "si", $role, $user_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_close($conn);
    return $success;
}
?>