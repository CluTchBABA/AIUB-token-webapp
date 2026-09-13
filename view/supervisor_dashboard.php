<?php
session_start();
include_once "../model/rooms.php";
include_once "../model/users.php";
include_once "../model/tokens.php";
include_once "../untils/table.generator.php";

if(!isset($_SESSION['id'])){
  header("Location: login.php");
  exit();
}

if($_SESSION['role'] != 'supervisor'){
  header('Location: ./supervisor_dashboard.php');
  exit();
}

$room_model = new Rooms();
$user_model = new Users();
$token_model = new Tokens();
$tg = new TableGenerator();

$my_room = $room_model ->get_room_by_supervisor((int)$_SESSION['id']);
?>

<!DOCTYPE html>
<html lang ='en'>
  <head>
    <meta charset = 'utf-8'>
    <meta name='viewport' content ='width=device-width, initial-scale=1'>
    <title>supervisor's Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
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
  <fieldset class="display-span" style="width: 320px;">
  <legend>Assign Teacher to My Room</legend>
  <form action ="../controller/supervisor-handler.php" method="post">
    <input type="hidden" name="action" value="assign_teacher">
    <input type="hidden" name="room_id" value="<?php echo (int)$my_room['id'];?>">
  Select Teacher:
    <select name="teacher_id" required>
    <option value="">--Select--</option>
      <?php
  $teachers =$user_model->get_unassigned_teachers();
  if($teachers){
    foreach ($teachers as $t) {
      echo '<option value="' .(int)$t['id'].'">'.htmlspecialchars($t['fullname']) . ' (' . htmlspecialchars($t['uni_id']) . ')</option>';
    }
  }
    ?>

    </select>
    <br><br>
    <input type="submit" value="Assign Teacher">
    </form>
    </fieldset>

    <br><br>

    <!---Currently assigned teachers in my room--->
    <div class="display-span" style="width:400px;">
    <?php
    $assigned=$room_model->get_teachers_in_room((int)$my_room['id']);
  if($assigned = $room_model->get_teacher_in_room((int)$my_room['id']);
    if ($assigned) {
       echo $tg->generate_table(
         $assigned,
         "Teachers currently assigned to your room:",
         ['Teacher Name', 'University ID']
         );
     }else{
       echo"<p>NO tecahers assigned to your room yet.</p>";
       }
       ?>
       </div>
       <br><br>

       <!--Request / Handle Load Balancing -->
<fieldset class="display-span" style="width: 320px;">
    <legend>Request Load Balancing</legend>
    <form action="../controller/supervisor-handler.php" method="post">
        <input type="hidden" name="action" value="request_load_balance">
        <input type="hidden" name="room_id" value="<?php echo (int)$my_room['id']; ?>">
        Reason / Note:<br>
        <textarea name="note" rows="3" cols="30" required placeholder="e.g. Room is overloaded, need redistribution"></textarea>
        <br><br>
        <input type="submit" value="Send Request to Admin">
    </form>
</fieldset>
         <br><br>

         <!---Report Problem to Admin----->
         <fieldset class="display-span" style="width: 320px;">
         <legend>Report Problem to Admin </legend>
         <form action ="../controller/supervisor-handler.php" method="post">
         <input type="hidden" name="action" value="report_problem">
         <input type="hidden" name="room_id" value="<?php echo (int)$my_room['id'];?>">
      Problem Description:<br>
      <textarea name="problem" rows="4" cols="30" required placeholder="Describe the issue..."></textarea>
      <br><br>
      <input type="submit" value="Report Problem">
      </form>
    </fieldset>
      <?php }?>
  <br><br>
      <form action ="logout.php" method="post">
        <input type="submit" value="Logout">
      </form>
    </body>
    </html>
      
