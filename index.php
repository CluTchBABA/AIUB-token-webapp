<?php
session_start();

if (isset($_SESSION['role'])) {
    header('Location ./view/' . $_SESSION['role'] . '_dashboard');
    exit();
}
?>

<html>
    <head>
        <title>Login Center</title>
        <link rel="stylesheet" type="text/css" href="../style.css">
    </head>
    <body>
        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <table class="app-table">
            <tr>
                <th>User type</th>
                <th>Login Page</th>
            </tr>
            <tr>
                <td>Admin</td>
                <td><a href="./view/admin_login.php">Admin Login</a></td>
            </tr>
            <tr>
                <td>Supervisor</td>
                <td><a href="./view/supervisor_login.php">Supervisor Login</a></td>
            </tr>
            <tr>
                <td>Faculty</td>
                <td><a href="./view/teacher_login.php">Teacher Login</a></td>
            </tr>
            <tr>
                <td>Student</td>
                <td><a href="./view/student_login.php">Student Login</a></td>
            </tr>
        </table>
    </body>
</html>