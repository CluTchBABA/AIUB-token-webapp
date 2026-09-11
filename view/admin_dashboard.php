<?php
session_start();
require_once(__DIR__ . '/../model/adminModel.php');

$users = getAllUsers();
$supervisors = getAllSupervisors();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AIUB Token System - Admin Control</title>
    <script src="../utils/admin_validation.js"></script>
</head>
<body>
    <h2>AIUB TOKEN MANAGEMENT SYSTEM - ADMIN PANEL</h2>

    <section>
        <h3>Create & Allocate Room</h3>
        <form action="../controller/adminController.php" method="POST" onsubmit="return validateRoomForm()">
            <input type="hidden" name="action" value="add_room">

            <label>Room Number:</label>
            <input type="text" id="room_name" name="room_name" placeholder="e.g. DN0205">

            <label>Capacity:</label>
            <input type="number" id="capacity" name="capacity" placeholder="e.g. 50">

            <label>Assign Supervisor:</label>
            <select name="supervisor_id">
                <option value="">Select Supervisor...</option>
                <?php foreach ($supervisors as $sup): ?>
                    <option value="<?= $sup['user_id'] ?>"><?= htmlspecialchars($sup['fullname']) ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Allocate Room</button>
        </form>
    </section>

    <hr>

    <section>
        <h3>University User Directory</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Current Role</th>
                    <th>Change Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['un_id']) ?></td>
                    <td><?= htmlspecialchars($u['fullname']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>
                        <form action="../controller/adminController.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_role">
                            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                            <select name="role">
                                <option value="Student" <?= $u['role'] == 'Student' ? 'selected' : '' ?>>Student</option>
                                <option value="Teacher" <?= $u['role'] == 'Teacher' ? 'selected' : '' ?>>Teacher</option>
                                <option value="Supervisor" <?= $u['role'] == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                <option value="Admin" <?= $u['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                            <button type="submit">Modify</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>