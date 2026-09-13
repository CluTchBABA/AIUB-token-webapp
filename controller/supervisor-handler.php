<?php
session_start();
include_once "../model/rooms.php";
include_once "../model/users.php";

if(!isset($_SESSION['id'])){
  header("Location:../view/supervisor_login.php");
  exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'supervisor') {
    header("Location: ../view/login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !=='POST' || !isset($_POST['action'])){
  header("Location:../view/supervisor_dashboard.php");
  exit();
}

$room_model = new Rooms();
$user_model = new Users();
$action = $_POST['action'];
$room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : 0;

// Verify this room for logged-in supervisor
$my_room = $room_model->get_room_by_supervisor((int)$_SESSION['id']);
if (!$my_room || (int)$my_room['id'] !== $room_id) {
    $_SESSION['error_message'] = "Invalid room or you are not assigned to this room.";
    header("Location: ../view/supervisor_dashboard.php");
    exit();
}

if($action === 'assign_teacher'){
  $teacher_id = isset($_POST['teacher_id']) ? (int)$_POST['teacher_id'] : 0;
  if($teacher_id <=0){
    $_SESSION['error_message'] ="Please select a teacher.";
    header("Location:../view/supervisor_dashboard.php");
    exit();
  }

$ok = $room_model->assign_teacher_to_room($teacher_id, $room_id);
  if($ok){
    $_SESSION['status_message'] ="Teacher assigned successfully.";
  }else{
    $_SESSION['error_message']="Failed to assignteacher. Teacher may already be assigned.";
  }
    header("Location:../view/supervisor_dashboard.php");
  exit();
}

if($action ==='request_load_balance'){
$note = isset($_POST['note']) ? trim($_POST['note']) : '';
 if ($note === '') {
   $_SESSION['error_message'] = "Please provide a reason for load balancing.";
    header("Location: ../view/supervisor_dashboard.php");
    exit();
}


   $log = date('Y-m-d H:i:s') . " | Supervisor ID: " . $_SESSION['id'] .
           " (" . $_SESSION['name'] . ") | Room: " . $my_room['name'] .
           " | Load Balance Request: " . $note . PHP_EOL;
    file_put_contents(__DIR__ . "/../supervisor_requests.log", $log, FILE_APPEND);

    $_SESSION['status_message'] = "Load balancing request sent to admin.";
    header("Location: ../view/supervisor_dashboard.php");
    exit();
}

if ($action === 'report_problem') {
    $problem = isset($_POST['problem']) ? trim($_POST['problem']) : '';
    if ($problem === '') {
        $_SESSION['error_message'] = "Please describe the problem.";
        header("Location: ../view/supervisor_dashboard.php");
        exit();
    }

    $log = date('Y-m-d H:i:s') . " | Supervisor ID: " . $_SESSION['id'] .
           " (" . $_SESSION['name'] . ") | Room: " . $my_room['name'] .
           " | Problem: " . $problem . PHP_EOL;
    file_put_contents(__DIR__ . "/../supervisor_requests.log", $log, FILE_APPEND);

    $_SESSION['status_message'] = "Problem reported to admin.";
    header("Location: ../view/supervisor_dashboard.php");
    exit();
}

header("Location: ../view/supervisor_dashboard.php");
exit();
