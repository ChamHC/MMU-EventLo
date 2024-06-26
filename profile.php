<?php
// Include the script that checks the user role and session
require_once 'trackRole.php';
$userRole = checkUserRole();

// Redirect to login page if user is not logged in
if ($userRole == null) {
    header('Location: index.php');
    exit();
} else {
    // Retrieve user ID from session
    $userId = $_SESSION['mySession'];
}

// Open a connection to the database
$conn = OpenCon();

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a SQL query to fetch user details based on userID
$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId); // Bind the userID parameter (assuming it's an integer)
$stmt->execute();
$result = $stmt->get_result();

// Check if a user with the specified ID exists
if ($result->num_rows > 0) {
    // Fetch user details and sanitize the output to prevent XSS
    $row = $result->fetch_assoc();
    $username = htmlspecialchars($row['username']);
    $gender = htmlspecialchars($row['gender']);
    $dateOfBirth = htmlspecialchars($row['dateOfBirth']);
    $contactNum = htmlspecialchars($row['contactNum']);
    $country = htmlspecialchars($row['country']);
    $role = htmlspecialchars($row['role']);
    $applyHost = $row['applyHost']; // Fetch applyHost field directly
} else {
    // Display an error message if no user is found
    echo "User with ID $userId not found";
}

// Close the database connection
CloseCon($conn);

// Handle form submission to update applyHost status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verifyHost'])) {
    // Check if the current user is eligible to apply for host status
    if ($applyHost == 0 && $role == 'User') {
        // Reopen the database connection
        $conn = OpenCon();
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Prepare and execute the SQL query to update applyHost status
        $sql = "UPDATE user SET applyHost = 1 WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        // Close the database connection again
        CloseCon($conn);
        // Update the applyHost variable to reflect the change
        $applyHost = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include CSS files for styling -->
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>User Profile</title>
</head>
<body>
    <!-- Include the header section -->
    <?php include 'header.php'; ?>

    <div class="profile-container">
        <h2>User Profile</h2>
        <hr>
        <div class="profile-row">
            <!-- Left column displaying user information -->
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
            <!-- Right column displaying user information -->
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
                <button type="submit" class="verify-host-button" name="verifyHost"
                    <?php echo ($applyHost == 1 && $role == 'Host') ? 'disabled' : ''; ?>
                    data-role="<?php echo htmlspecialchars($role); ?>"
                    data-applyHost="<?php echo htmlspecialchars($applyHost); ?>">
                    <?php
                    // Display appropriate button text based on user's role and applyHost status
                    if ($applyHost == 0 && $role == 'User') {
                        echo 'Verify';
                    } elseif ($applyHost == 1 && $role == 'User') {
                        echo 'Verifying...';
                    } elseif ($role == 'Host') {
                        echo 'Verify Success';
                    } else {
                        echo 'Something wrong. Please contact us.';
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

    <!-- Include the footer section -->
    <?php include 'footer.php'; ?>
</body>
</html>
