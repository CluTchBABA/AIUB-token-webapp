<?php
session_star();
include_once "../model/rooms.php";
include_once "../model/users.php";
include_once "../model/tokens.php";
include_once "../untils/table.generator.php";

if(!isset($_SESSION['id'])){
  header("Location: login.php");
  exit();
}

if($_SEESION['role'] != 'supervisor'){
  header ('Location : ./' .$_SESSION['role'] . '_dashboard.php');
  exit();
}

$my_room = $room_model ->get_room_by_supervisor((int)$_SEESION['id']);
?>

<!DOCTYPE html>
<html lang ='en'>
  <head>
    <meta charset = 'utf-8'>
    <meta name='viewport' content ='width=device-width, initial-scale=1'>
    <title>supervisor's Dashboard</title>
    <link rel="stylesheet" type="text/css' href="../style.css">
  </head>
  <body>
<h1>Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></h1>

<?php if (isset($_SESSION['status_message'])): ?>
    <p><?php echo htmlspecialchars($_SESSION['status_message']); ?></p>
    <?php unset($_SESSION['status_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <p class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php
if (!$my_room) {
    echo "<p>You are not assigned to any room yet. Please contact the admin.</p>";
} else {
    echo "<p>Your room: <strong>" . htmlspecialchars($my_room['name']) . "</strong> (Capacity: " . (int)$my_room['capacity'] . ", Current load: " . (int)$my_room['current_load'] . ")</p>";

    // Show load across rooms (for load balancing awareness)
    $tc = $room_model->get_number_of_tokens_in_each_room();
    if ($tc) {
        echo $tg->generate_table(
            $tc,
            "Students waiting in different rooms:",
            ['Room Name', 'Supervisor Name', 'Number of students waiting']
        );
    }
?>
<br><br>

 <!--Assign Teacher to Room-->
  <fieldset class="display-span" style="width: px;">
  <legend>Assign Teacher to My Room</legend>
  <form>
    
  </form>
