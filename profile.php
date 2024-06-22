<?php
// Check user role and session
require_once 'trackRole.php';
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: index.php');
    exit();
} else {
    $userId = $_SESSION['mySession'];
}

// Fetch user details from the database
$conn = OpenCon();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId); // Assuming userID is an integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = htmlspecialchars($row['username']);
    $gender = htmlspecialchars($row['gender']);
    $dateOfBirth = htmlspecialchars($row['dateOfBirth']);
    $contactNum = htmlspecialchars($row['contactNum']);
    $country = htmlspecialchars($row['country']);
    $role = htmlspecialchars($row['role']);
    $applyHost = $row['applyHost']; // Fetch applyHost field
} else {
    echo "User with ID $userId not found";
}

// Close database connection
CloseCon($conn);

// Process button click to update applyHost
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verifyHost'])) {
    if ($applyHost == 0 && $role == 'User') {
        $conn = OpenCon();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "UPDATE user SET applyHost = 1 WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        CloseCon($conn);
        // Refresh $applyHost after update
        $applyHost = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/profile.css">
    <style>
        .verify-host-button {
            <?php if ($applyHost == 0 && $role == 'User') : ?>
                background-color: blue;
                cursor: pointer;
            <?php elseif ($applyHost == 1 && $role == 'User') : ?>
                background-color: grey;
                cursor: not-allowed;
            <?php elseif ($role == 'Host') : ?>
                background-color: #006b75; 
                cursor: not-allowed;
            <?php endif; ?>
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .verify-host-button:hover {
            <?php if ($applyHost == 0 && $role == 'User') : ?>
                background-color: darkblue;
            <?php endif; ?>
        }
        .edit-profile-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .edit-profile-button:hover {
            background-color: #45a049;
        }
    </style>
    <title>User Profile</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <h2>User Profile</h2>
        <hr>
        <div class="profile-row">
            <!-- Left column -->
            <div class="left-column">
                <?php if (isset($username)) : ?>
                    <p><img src="images/profile.png" alt="Profile Icon" class="profile-icon"> <strong>Username:</strong> <?php echo $username; ?></p>
                <?php endif; ?>
                <?php if (isset($gender)) : ?>
                    <p><img src="images/profile/gender_icon.png" alt="Gender Icon" class="profile-icon"> <strong>Gender:</strong> <?php echo $gender; ?></p>
                <?php endif; ?>
                <?php if (isset($dateOfBirth)) : ?>
                    <p><img src="images/profile/birthday_icon.png" alt="Birthday Icon" class="profile-icon"> <strong>Date of Birth:</strong> <?php echo $dateOfBirth; ?></p>
                <?php endif; ?>
            </div>
            <!-- Right column -->
            <div class="right-column">
                <?php if (isset($contactNum)) : ?>
                    <p><img src="images/profile/phone_icon.png" alt="Phone Icon" class="profile-icon"> <strong>Contact Number:</strong> <?php echo $contactNum; ?></p>
                <?php endif; ?>
                <?php if (isset($country)) : ?>
                    <p><img src="images/profile/country_icon.png" alt="Country Icon" class="profile-icon"> <strong>Country:</strong> <?php echo $country; ?></p>
                <?php endif; ?>
                <?php if (isset($role)) : ?>
                    <p><img src="images/profile/role_icon.png" alt="Role Icon" class="profile-icon"> <strong>Role:</strong> <?php echo $role; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($role != 'Admin'): ?>
            <!-- Verify Host Button -->
            <form method="post">
                <button type="submit" class="verify-host-button" name="verifyHost" <?php echo ($applyHost == 1 || $role == 'Host') ? 'disabled' : ''; ?>>
                    <?php
                    if ($applyHost == 0 && $role == 'User') {
                        echo 'Verify';
                    } elseif ($applyHost == 1 && $role == 'User') {
                        echo 'Verifying...';
                    } elseif ($role == 'Host') {
                        echo 'Verify Success';
                    }
                    ?>
                </button>
            </form>

            <!-- History Button -->
            <a href="history.php" class="edit-profile-button">History</a>
        <?php endif; ?>

        <!-- Edit Profile Button -->
        <a href="editProfile.php" class="edit-profile-button">Edit Profile</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
