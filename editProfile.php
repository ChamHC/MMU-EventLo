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

// Valid countries array
$validCountries = [
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

// Initialize error messages
$errors = [];

// Include database connection and other necessary functions

// Check user role and obtain user ID from session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $contactNum = $_POST['contactNum'];
    $country = $_POST['country'];

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
        $conn = OpenCon();

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
}

// Fetch user details for pre-populating the form
$conn = OpenCon();
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
        <?php
        // Display error messages if any
        if (!empty($errors)) {
            echo '<div class="error-container">';
            foreach ($errors as $error) {
                echo '<p>' . htmlspecialchars($error) . '</p>';
            }
            echo '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="Male" <?php if ($gender == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($gender == 'Female') echo 'selected'; ?>>Female</option>
                <option value="N/A" <?php if ($gender == 'N/A') echo 'selected'; ?>>N/A</option>
            </select>
            <label for="dateOfBirth">Date of Birth:</label>
            <input type="date" id="dateOfBirth" name="dateOfBirth" value="<?php echo htmlspecialchars($dateOfBirth); ?>" required>
            <label for="contactNum">Contact Number:</label>
            <input type="text" id="contactNum" name="contactNum" value="<?php echo htmlspecialchars($contactNum); ?>" required>
            <label for="country">Country:</label>
            <select id="country" name="country" required>
                <?php foreach ($validCountries as $c) { ?>
                    <option value="<?php echo $c; ?>" <?php if ($country == $c) echo 'selected'; ?>><?php echo $c; ?></option>
                <?php } ?>
            </select>
            <button type="submit">Save Changes</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
