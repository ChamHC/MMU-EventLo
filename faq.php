<?php
require_once 'trackRole.php';

// Check user role and redirect if not logged in
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: login.php');
    exit();
} else {
    // Retrieve user ID from session
    $userId = $_SESSION['mySession'];
}

// Open database connection
$conn = OpenCon();

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a SQL query to fetch FAQ data ordered by severityQuestion
$sql = "SELECT faqID, question, answer, severityQuestion FROM faq ORDER BY FIELD(severityQuestion, 'High', 'Medium', 'Low')";
$result = $conn->query($sql);

// Check if the query execution was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare an associative array to hold FAQs grouped by severityQuestion
$groupedFaqs = array(
    'High' => array(),
    'Medium' => array(),
    'Low' => array()
);

// Fetch FAQs and group them by severityQuestion
while ($row = $result->fetch_assoc()) {
    $faqId = $row['faqID'];
    $question = htmlspecialchars($row['question']); // Sanitize the question
    $answer = htmlspecialchars($row['answer']); // Sanitize the answer
    $severityQuestion = $row['severityQuestion'];

    // Ensure severityQuestion is not empty and belongs to predefined categories
    if (!empty($severityQuestion) && isset($groupedFaqs[$severityQuestion])) {
        $faq = array(
            'faqId' => $faqId,
            'question' => $question,
            'answer' => $answer
        );
        // Add the FAQ to the corresponding severity group
        $groupedFaqs[$severityQuestion][] = $faq;
    }
}

// Close the database connection
CloseCon($conn);
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
    <link rel="stylesheet" href="css/faqStyle.css">
    <title>Frequently Asked Questions (FAQ)</title>
</head>
<body>
    <!-- Include the header section -->
    <?php include 'header.php'; ?>

    <div class="main">
        <h1 class="title">Frequently Asked Questions (FAQ)</h1>
        <div class="faq-container">
            <?php foreach (['High', 'Medium', 'Low'] as $severityQuestion): ?>
                <!-- Check if there are FAQs for the current severity level -->
                <?php if (!empty($groupedFaqs[$severityQuestion])): ?>
                    <?php foreach ($groupedFaqs[$severityQuestion] as $faq): ?>
                        <div class="faq-item">
                            <h3 class="question"><?= htmlspecialchars($faq['question']) ?><span class="toggle-icon">></span></h3>
                            <p class="answer"><?= htmlspecialchars($faq['answer']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Include the footer section -->
    <?php include 'footer.php'; ?>

    <!-- Link to the FAQ form page -->
    <div class="faq-icon-container">
        <a href="faqForm.php">
            <img src="images/faq icon.png" alt="FAQ Icon">
        </a>
    </div>

    <!-- JavaScript to toggle FAQ answers visibility -->
    <script>
        document.querySelectorAll('.question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                // Toggle visibility of the answer
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    question.querySelector('.toggle-icon').textContent = '>';
                } else {
                    answer.style.display = 'block';
                    question.querySelector('.toggle-icon').textContent = 'v';
                }
            });
        });
    </script>
</body>
</html>
