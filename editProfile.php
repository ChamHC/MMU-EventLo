<?php
// Check user role and session
require_once 'trackRole.php'; // Include file that checks user role
$userRole = checkUserRole(); // Check the user's role using a function from an external file
if ($userRole == null) {
    header('Location: index.php'); // Redirect to index.php if user role is not valid
    exit();
} else {
    $userId = $_SESSION['mySession']; // Retrieve user ID from the session
}

// Valid countries array
$validCountries = [
    // List of valid countries for the dropdown menu
    "AFGHANISTAN", "ALBANIA", "ALGERIA", "ANDORRA", "ANGOLA", "ANTIGUA AND BARBUDA", "ARGENTINA", "ARMENIA", "AUSTRALIA",
    "AUSTRIA", "AZERBAIJAN", "BAHAMAS", "BAHRAIN", "BANGLADESH", "BARBADOS", "BELARUS", "BELGIUM", "BELIZE", "BENIN",
    "BHUTAN", "BOLIVIA", "BOSNIA AND HERZEGOVINA", "BOTSWANA", "BRAZIL", "BRUNEI", "BULGARIA", "BURKINA FASO", "BURUNDI",
    "CABO VERDE", "CAMBODIA", "CAMEROON", "CANADA", "CENTRAL AFRICAN REPUBLIC", "CHAD", "CHILE", "CHINA", "COLOMBIA",
    "COMOROS", "CONGO", "COSTA RICA", "CROATIA", "CUBA", "CYPRUS", "CZECH REPUBLIC", "DENMARK", "DJIBOUTI", "DOMINICA",
    "DOMINICAN REPUBLIC", "EAST TIMOR", "ECUADOR", "EGYPT", "EL SALVADOR", "EQUATORIAL GUINEA", "ERITREA", "ESTONIA",
    "ESWATINI", "ETHIOPIA", "FIJI", "FINLAND", "FRANCE", "GABON", "GAMBIA", "GEORGIA", "GERMANY", "GHANA", "GREECE",
    "GRENADA", "GUATEMALA", "GUINEA", "GUINEA-BISSAU", "GUYANA", "HAITI", "HONDURAS", "HUNGARY", "ICELAND", "INDIA",
    "INDONESIA", "IRAN", "IRAQ", "IRELAND", "ISRAEL", "ITALY", "IVORY COAST", "JAMAICA", "JAPAN", "JORDAN", "KAZAKHSTAN",
    "KENYA", "KIRIBATI", "KOSOVO", "KUWAIT", "KYRGYZSTAN", "LAOS", "LATVIA", "LEBANON", "LESOTHO", "LIBERIA", "LIBYA",
    "LIECHTENSTEIN", "LITHUANIA", "LUXEMBOURG", "MADAGASCAR", "MALAWI", "MALAYSIA", "MALDIVES", "MALI", "MALTA",
    "MARSHALL ISLANDS", "MAURITANIA", "MAURITIUS", "MEXICO", "MICRONESIA", "MOLDOVA", "MONACO", "MONGOLIA", "MONTENEGRO",
    "MOROCCO", "MOZAMBIQUE", "MYANMAR", "NAMIBIA", "NAURU", "NEPAL", "NETHERLANDS", "NEW ZEALAND", "NICARAGUA", "NIGER",
    "NIGERIA", "NORTH KOREA", "NORTH MACEDONIA", "NORWAY", "OMAN", "PAKISTAN", "PALAU", "PALESTINE", "PANAMA", "PAPUA NEW GUINEA",
    "PARAGUAY", "PERU", "PHILIPPINES", "POLAND", "PORTUGAL", "QATAR", "ROMANIA", "RUSSIA", "RWANDA", "SAINT KITTS AND NEVIS",
    "SAINT LUCIA", "SAINT VINCENT AND THE GRENADINES", "SAMOA", "SAN MARINO", "SAO TOME AND PRINCIPE", "SAUDI ARABIA",
    "SENEGAL", "SERBIA", "SEYCHELLES", "SIERRA LEONE", "SINGAPORE", "SLOVAKIA", "SLOVENIA", "SOLOMON ISLANDS", "SOMALIA",
    "SOUTH AFRICA", "SOUTH KOREA", "SOUTH SUDAN", "SPAIN", "SRI LANKA", "SUDAN", "SURINAME", "SWEDEN", "SWITZERLAND",
    "SYRIA", "TAIWAN", "TAJIKISTAN", "TANZANIA", "THAILAND", "TOGO", "TONGA", "TRINIDAD AND TOBAGO", "TUNISIA", "TURKEY",
    "TURKMENISTAN", "TUVALU", "UGANDA", "UKRAINE", "UNITED ARAB EMIRATES", "UNITED KINGDOM", "UNITED STATES", "URUGUAY",
    "UZBEKISTAN", "VANUATU", "VATICAN CITY", "VENEZUELA", "VIETNAM", "YEMEN", "ZAMBIA", "ZIMBABWE"
];

// Initialize error messages array
$errors = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $username = $_POST['username']; // Retrieve and validate username input
    $gender = $_POST['gender']; // Retrieve and validate gender input
    $dateOfBirth = $_POST['dateOfBirth']; // Retrieve and validate date of birth input
    $contactNum = $_POST['contactNum']; // Retrieve and validate contact number input
    $country = $_POST['country']; // Retrieve and validate country input

    // Validate username
    if (!preg_match("/^[a-zA-Z]+(?: [a-zA-Z]+)*$/", $username)) {
        $errors[] = "Username can only contain letters and single spaces between words.";
    }

    // Validate gender
    if (!in_array($gender, ["Male", "Female", "N/A"])) {
        $errors[] = "Gender must be 'Male', 'Female', or 'N/A'.";
    }

    // Validate date of birth
    $today = new DateTime();
    $birthdate = new DateTime($dateOfBirth);
    $minAge = 18;
    $age = $birthdate->diff($today)->y;
    if ($age < $minAge) {
        $errors[] = "You must be at least 18 years old.";
    }

    // Validate contact number
    if (!preg_match("/^[0-9\-]+$/", $contactNum)) {
        $errors[] = "Contact Number can only contain numbers and a single dash.";
    }

    // Validate country
    if (!in_array(strtoupper($country), $validCountries)) {
        $errors[] = "Please select a valid country from the list.";
    }

    // If no errors, proceed with database update
    if (empty($errors)) {
        // Perform database update
        $conn = OpenCon(); // Connect to the database

        // Prepare update statement
        $sql = "UPDATE user SET username=?, gender=?, dateOfBirth=?, contactNum=?, country=? WHERE userID=?";
        $stmt = $conn->prepare($sql); // Prepare SQL statement

        // Bind parameters and execute update
        $stmt->bind_param("sssssi", $username, $gender, $dateOfBirth, $contactNum, $country, $userId); // Bind parameters
        $stmt->execute(); // Execute SQL statement

        // Redirect to profile.php regardless of update success
        header('Location: profile.php');
        exit();
    }
}

// Fetch user details for pre-populating the form
$conn = OpenCon(); // Connect to the database
$sql = "SELECT * FROM user WHERE userID = ?";
$stmt = $conn->prepare($sql); // Prepare SQL statement
$stmt->bind_param("i", $userId); // Bind parameter
$stmt->execute(); // Execute SQL statement
$result = $stmt->get_result(); // Get result from executed SQL statement

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); // Fetch user details
    $username = $row['username']; // Assign username from fetched details
    $gender = $row['gender']; // Assign gender from fetched details
    $dateOfBirth = $row['dateOfBirth']; // Assign date of birth from fetched details
    $contactNum = $row['contactNum']; // Assign contact number from fetched details
    $country = $row['country']; // Assign country from fetched details
} else {
    echo "User with ID $userId not found"; // Display message if user not found
}

// Close database connection
CloseCon($conn); // Close database connection
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
    <?php include 'header.php'; ?> <!-- Include header.php for consistency -->

    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <hr>
        <?php
        // Display error messages if any
        if (!empty($errors)) {
            echo '<div class="error-container">'; // Start error container
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>'; // Display each error message
            }
            echo '</div>'; // End error container
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> <!-- Form action and method -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required> <!-- Input field for username -->
            <label for="gender">Gender:</label>
            <select id="gender" name="gender"> <!-- Select dropdown for gender -->
                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="N/A" <?php if ($gender == 'N/A') echo 'selected'; ?>>N/A</option>
            </select>
            <label for="dateOfBirth">Date of Birth:</label>
            <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>" required> <!-- Input field for date of birth -->
            <label for="contactNum">Contact Number:</label>
            <input type="text" id="contactNum" name="contactNum" value="<?php echo htmlspecialchars($contactNum); ?>" required> <!-- Input field for contact number -->
            <label for="country">Country:</label>
            <select id="country" name="country" required> <!-- Select dropdown for country -->
                <?php foreach ($validCountries as $c) { ?>
                    <option value="<?php echo $c; ?>" <?php if ($country == $c) echo 'selected'; ?>><?php echo $c; ?></option> <!-- Populate options based on valid countries -->
                <?php } ?>
            </select>
            <button type="submit">Save Changes</button> <!-- Submit button -->
        </form>
    </div>

    <?php include 'footer.php'; ?> <!-- Include footer.php for consistency -->
</body>
</html>
