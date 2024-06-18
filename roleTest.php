<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>Role Test</title>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <h1>Role Test</h1>

    <?php
    require 'trackRole.php';

    // Check user role
    $userRole = checkUserRole();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($userRole == 'Admin' && isset($_POST['Admin'])) {
            echo "<script>showAlert('Admin: Correct role');</script>";
        } elseif ($userRole == 'Host' && isset($_POST['Host'])) {
            echo "<script>showAlert('Host: Correct role');</script>";
        } elseif ($userRole == 'User' && isset($_POST['User'])) {
            echo "<script>showAlert('User: Correct role');</script>";
        } else {
            echo "<script>showAlert('Error: Incorrect role');</script>";
        }
    }
    ?>

    <!-- 只有对应的role($userRole)才会显示出来 -->
    <form method="post">
        <?php if ($userRole == 'Admin'): ?>
            <button type="submit" name="Admin">Admin</button>
        <?php endif; ?>
        <?php if ($userRole == 'Host'): ?>
            <button type="submit" name="Host">Host</button>
        <?php endif; ?>
        <?php if ($userRole == 'User'): ?>
            <button type="submit" name="User">User</button>
        <?php endif; ?>
    </form>
</body>
</html>
