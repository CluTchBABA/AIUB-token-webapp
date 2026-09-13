<?php
session_start();
include_once "../model/Tokens.php";
if (!isset($_SESSION['id'])) {
    header("Location: ./login.php");
    exit();
}
$token_model = new Tokens();
$message = $_SESSION['error_message'] ?? '';
$token_id = null;

if (isset($_GET['token_id']) && $_GET['token_id'] !== '') {
    $token_id = (int)$_GET['token_id'];
} elseif (isset($_SESSION['token_id'])) {
    $token_id = (int)$_SESSION['token_id'];
} else {
    $waiting_token = $token_model->get_waiting_token_for_user((int)$_SESSION['id']);
    if ($waiting_token) {
        $token_id = (int)$waiting_token['token_id'];
        $_SESSION['token_id'] = $token_id;
    }
}

$tokens_before = [];
$tokens_after = [];
$formatted_token_id = '';

if ($token_id) {
    $tokens_before = $token_model->get_tokens_before($token_id);
    $tokens_after = $token_model->get_tokens_after($token_id);
    $formatted_token_id = '#T-' . htmlspecialchars((string)$token_id);
}

unset($_SESSION['error_message']);
?>
<html lang="en">
<head>
<!--    <title>Token View</title>-->
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h1>Token View</h1>
<h1>Apply for Registration Token</h1>

<?php if ($message): ?>
    <p class="error">
        <?= htmlspecialchars($message); ?>
    </p>
<?php endif; ?>

<?php if ($token_id !== null): ?>
    <p>
        Your token has been created. Please note your token number:
        <strong><?= $formatted_token_id; ?></strong>
    </p>

    <h2>Tokens before you</h2>
    <?php if (!empty($tokens_before)): ?>
        <table class="app-table">
            <thead>
            <tr>
                <th>Token</th>
                <th>Student</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tokens_before as $token): ?>
                <tr>
                    <td><?= '#T-' . htmlspecialchars((string)$token['token_id']); ?></td>
                    <td><?= htmlspecialchars($token['fullname']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tokens are before yours.</p>
    <?php endif; ?>

    <h2>Tokens after you</h2>
    <?php if (!empty($tokens_after)): ?>
        <table class="app-table">
            <thead>
            <tr>
                <th>Token</th>
                <th>Student</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tokens_after as $token): ?>
                <tr>
                    <td><?= '#T-' . htmlspecialchars((string)$token['token_id']); ?></td>
                    <td><?= htmlspecialchars($token['fullname']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tokens are after yours.</p>
    <?php endif; ?>
<?php endif; ?>



<form action="../controller/token-handler.php" method="POST">

    <input type="submit" <?php
    $token_exists = $token_model->token_already_exists($_SESSION['id']);
    if($token_exists) echo 'style="visibility: hidden;"' ?>value="Generate Token" name="generate_token"></input>
</form>


<p><a href="student_dashboard.php">Back to Dashboard</a></p>


</body>
</html>