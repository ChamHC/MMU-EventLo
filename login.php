<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoginPage</title>
    <link href="css/login.css" rel="stylesheet">
</head>
<body>
    <div class="split-screen">
        <div class="left">
            <section class="copy">
                <img src="images/logo2.png" width="400">
                <h1 class="title">MMU Event Organizer</h1>
            </section>
        </div>
        <div class="right">
            <form action ="loginfunc.php" method="post">
                <div class="input-container name">
                    <label for="username">Username</label>
                    <input type="text" name="username" required ="required">
                </div>
                <div class="input-container password">
                    <label for="password">Password</label>
                    <input type="password" name="password" required="required">
                </div>
                <button class="login-btn" type="submit" name="login">Login</button>
                <hr class="divider">
                <a href="register.php" class="register-btn">Register</a>
            </form>
        </div>
    </div>
</body>
</html>