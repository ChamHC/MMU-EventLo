<?php
// Include database connection file
include 'db_connect.php';

// Define variables to store user input and error messages
$question = $email = $severityQuestion = "";
$questionErr = $emailErr = $severityErr = "";
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and process form data
    if (empty($_POST["question"])) {
        $questionErr = "Question is required";
    } else {
        $question = test_input($_POST["question"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        // Check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["severity"])) {
        $severityErr = "Severity is required";
    } else {
        $severityQuestion = test_input($_POST["severity"]);
    }

    // If no errors, insert data into database
    if (empty($questionErr) && empty($emailErr) && empty($severityErr)) {
        // Connect to database
        $conn = OpenCon();

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assume getting the following data from the form
        $question = mysqli_real_escape_string($conn, $_POST['question']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $severityQuestion = mysqli_real_escape_string($conn, $_POST['severityQuestion']);
        $userID = 1; // Assume you have a valid userID from somewhere, here assuming it's 1

        // Prepare SQL statement to insert data into 'faq' table in 'mmu_event' database
        $sql = "INSERT INTO faq (question, answer, email, severityQuestion, userID)
                VALUES ('$question', '', '$email', '$severityQuestion', '$userID')";

        // Execute insert statement
        if ($conn->query($sql) === TRUE) {
            $message = "Your question is submitted";
            // Clear form data, optional
            $question = $email = $severityQuestion = "";
        } else {
            $message = "Failed to submit: " . $conn->error;
        }

        // Close database connection
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
    <link rel="stylesheet" href="css/faqFormStyle.css"> <!-- Create this CSS file for form styling -->
    <title>FAQ Question Form</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <h1 class="form-title">FAQ Question Form</h1>
        <p>Please ask your question below, make sure it is short but clear.</p>

        <?php if (!empty($message)) : ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="question">Question:</label>
                <textarea id="question" name="question" rows="5" required><?php echo $question; ?></textarea>
                <span class="error"><?php echo $questionErr; ?></span>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="e.g., myname@example.com" required>
                <span class="error"><?php echo $emailErr; ?></span>
            </div>
            <div class="form-group">
                <label for="severity">Severity of Your question:</label>
                <select id="severity" name="severity" required>
                    <option value="">Select severity</option>
                    <option value="High" <?php if ($severityQuestion == "High") echo "selected"; ?>>High</option>
                    <option value="Medium" <?php if ($severityQuestion == "Medium") echo "selected"; ?>>Medium</option>
                    <option value="Low" <?php if ($severityQuestion == "Low") echo "selected"; ?>>Low</option>
                </select>
                <span class="error"><?php echo $severityErr; ?></span>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
