<?php
require_once 'trackRole.php';

// Check user role and redirect if not logged in
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: login.php');
    exit();
} else {
    // Assuming $_SESSION['mySession'] is set correctly from your authentication logic
    $userID = $_SESSION['mySession'] ?? null;

    // Ensure userID is set and valid
    if (!$userID) {
        header('Location: login.php');
        exit();
    }
}

// Define variables to store user input and error messages
$question = "";
$questionErr = "";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process form data
    if (empty($_POST["question"])) {
        $questionErr = "Question is required";
    } else {
        $question = test_input($_POST["question"]);
    }

    // If no errors, insert data into database
    if (empty($questionErr)) {
        // Connect to database
        $conn = OpenCon();

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Sanitize inputs
        $question = mysqli_real_escape_string($conn, $_POST['question']);

        // Prepare SQL statement to fetch user's email
        $stmt = $conn->prepare("SELECT email FROM user WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();

        // Prepare SQL statement to insert data into 'faq' table
        $sql = "INSERT INTO faq (question, answer, email, userID)
                VALUES (?, '', ?, ?)";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $question, $email, $userID);

        // Execute insert statement
        if ($stmt->execute()) {
            $message = "Your question is submitted";
            // Clear form data, optional
            $question = "";
        } else {
            $message = "Failed to submit: " . $conn->error;
        }

        // Close statement and database connection
        $stmt->close();
        CloseCon($conn);

        // Redirect to faq.php page
        header("Location: faq.php");
        exit();
    }
}

// Function to sanitize user input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
    <link rel="stylesheet" href="css/faqFormStyle.css"> 
    <title>FAQ Question Form</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <h1 class="form-title">FAQ Question Form</h1>
        <p>Please ask your question below:</p>

        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="question">Question:</label>
                <textarea id="question" name="question" rows="5" required><?php echo $question; ?></textarea>
                <span class="error"><?php echo $questionErr; ?></span>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
