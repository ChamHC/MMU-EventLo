<?php
    // Include database connection file
    require 'db_connect.php';

    // Set the user ID to be displayed
    $userId = 1;

    // Fetch user details from the database
    $conn = OpenCon();

    // Prepare statement to fetch user details
    $sql = "SELECT * FROM user WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId); // Assuming userID is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $gender = $row['gender'];
        $dateOfBirth = $row['dateOfBirth'];
        $contactNum = $row['contactNum'];
        $country = $row['country'];
        $role = $row['role'];
        // Fetch more fields as needed
    } else {
        echo "User with ID $userId not found";
    }

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
    <link rel="stylesheet" href="css/profileStyle.css"> <!-- Link to profileStyle.css -->
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
                    <p><img src="images/profile/gender_icon.png" alt="Gender Icon" class="profile-icon"> <strong>Username:</strong> <?php echo $username; ?></p>
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
                <!-- Add more fields as needed -->
            </div>
        </div>

        <!-- Example: Edit Profile Button -->
        <a href="edit_profile.php" class="edit-profile-button">Edit Profile</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
