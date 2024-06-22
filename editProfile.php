<?php
// Include file to check user role
require_once 'trackRole.php';

// Check user role
$userRole = checkUserRole();
if ($userRole == null) {
    // Redirect to index.php if user role is not valid
    header('Location: index.php');
    exit();
} else {
    // Get user ID from session if user role is valid
    $userId = $_SESSION['mySession'];
}

// Connect to the database
$conn = OpenCon();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $contactNum = $_POST['contactNum'];
    $country = $_POST['country'];
    $userId = $_SESSION['mySession']; // Get user ID from session

    // Prepare update statement
    $sql = "UPDATE user SET username=?, gender=?, dateOfBirth=?, contactNum=?, country=? WHERE userID=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute update
    $stmt->bind_param("sssssi", $username, $gender, $dateOfBirth, $contactNum, $country, $userId);
    $stmt->execute();

    // Redirect to profile.php regardless of update success
    header('Location: profile.php');
    exit();
}

// Query user details to populate the form
$userId = $_SESSION['mySession'];
$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $gender = $row['gender'];
    $dateOfBirth = $row['dateOfBirth'];
    $contactNum = $row['contactNum'];
    $country = $row['country'];
    // Fetch more fields as needed
} else {
    echo "User with ID $userId not found";
}

// Close database connection
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/editprofileStyle.css"> <!-- Link to editprofileStyle.css -->
    <title>Edit Profile</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <hr>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
            <label for="dateOfBirth">Date of Birth:</label>
            <input type="text" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>">
            <label for="contactNum">Contact Number:</label>
            <input type="text" id="contactNum" name="contactNum" value="<?php echo htmlspecialchars($contactNum); ?>">
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>">
            <button type="submit">Save Changes</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
