<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['role'] != 'student') {
    header('Location: ./' . $_SESSION['role'] . '_dashboard.php');
    exit();
}
if (isset($_POST['view_profile'])) {
    header("Location: ../view/student_view_profile.php");
    exit();
}
if (isset($_POST['update_profile'])) {
    header("Location: student_update_profile.php");
    exit();
}
?>

<html>
<head>
    <title>Student's Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<h1>Hello, <?php echo $_SESSION['name'] ?></h1>

<?php if (isset($_SESSION['status_message'])): ?>
    <p><?php echo htmlspecialchars($_SESSION['status_message']); ?></p>
    <?php unset($_SESSION['status_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <p class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<a href="../view/token_view.php">
    <h3>Generate my token</h3>
</a>
<a href="../controller/report_handler.php">
    <h3>Report Absence</h3>
</a>
<form action="" method="post">
    <button type="submit" name="view_profile">View Profile</button>
    <button type="submit" name="update_profile">Update Profile</button>
</form>

<form action="logout.php" method="post">
    <input type="submit" value="Logout">
</form>
</body>
</html>