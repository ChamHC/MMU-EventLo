<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RegisterPage</title>
    <link href="css/register.css" rel="stylesheet">
    <script>
        // country字段转换为大写
        function toUpperCase(input) {
            input.value = input.value.toUpperCase();
        }

        // 日期输入的最大值为今天的日期
        function setMaxDate() {
            var today = new Date();
            var day = ("0" + today.getDate()).slice(-2);
            var month = ("0" + (today.getMonth() + 1)).slice(-2);
            var todayDate = today.getFullYear() + "-" + month + "-" + day;
            document.getElementById('dob').setAttribute('max', todayDate);
        }

        window.onload = setMaxDate;
    </script>
</head>
<body>
    <div class="container">
        <form action="registerfunc.php" method="post">
            <div class="input-container">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" required="required">
            </div>
            <div class="input-container">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required="required">
            </div>
            <div class="input-container gender">
                <label>Gender</label>
                <p>
                <label for="male"> <input type="radio" id="male" name="gender" value="Male"> Male</label>
                <label for="female"> <input type="radio" id="female" name="gender" value="Female"> Female</label>
                <label for="confidentiality"><input type="radio" id="confidentiality" name="gender" value="N/A"> Confidentiality</label>
                </p>
            </div>
            <div class="input-container">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required="required">
            </div>
            <div class="input-container">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" required="required">
            </div>
            <div class="input-container">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required="required">
            </div>
            <div class="input-container">
                <label for="country">Country</label>
                <input type="text" id="country" name="country" required="required" oninput="toUpperCase(this)">
            </div>
            <hr class="divider">
            <button class="sign-up-btn" type="submit" name="signUp">Sign Up</button>
        </form>
    </div>
</body>
</html>
