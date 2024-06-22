<?php
require_once 'trackRole.php';

// Check user role and redirect if not logged in
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: login.php');
    exit();
} else {
    $userId = $_SESSION['mySession'];
}

// Open database connection (assuming OpenCon() and CloseCon() are defined elsewhere)
$conn = OpenCon();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch FAQ data ordered by severityQuestion
$sql = "SELECT faqID, question, answer, severityQuestion FROM faq ORDER BY FIELD(severityQuestion, 'High', 'Medium', 'Low')";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare associative array to hold FAQs grouped by severityQuestion
$groupedFaqs = array(
    'High' => array(),
    'Medium' => array(),
    'Low' => array()
);

// Fetch FAQs and group them by severityQuestion
while ($row = $result->fetch_assoc()) {
    $faqId = $row['faqID'];
    $question = htmlspecialchars($row['question']);
    $answer = htmlspecialchars($row['answer']);
    $severityQuestion = $row['severityQuestion'];

    $faq = array(
        'faqId' => $faqId,
        'question' => $question,
        'answer' => $answer
    );

    // Push FAQ into appropriate severityQuestion group
    $groupedFaqs[$severityQuestion][] = $faq;
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
    <link rel="stylesheet" href="css/faqStyle.css">
    <title>Frequently Asked Questions (FAQ)</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <h1 class="title">Frequently Asked Questions (FAQ)</h1>
        <div class="faq-container">
            <?php
            foreach (['High', 'Medium', 'Low'] as $severityQuestion) {
                if (!empty($groupedFaqs[$severityQuestion])) {
                    echo "<div class='severity-section'>";
                    echo "<h2 class='severity-title'>$severityQuestion Severity</h2>";
                    foreach ($groupedFaqs[$severityQuestion] as $faq) {
                        echo "<div class='faq-item'>";
                        echo "<h3 class='question'>" . $faq['question'] . "<span class='toggle-icon'>></span></h3>";
                        echo "<p class='answer'>" . $faq['answer'] . "</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>

    <div class="faq-icon-container">
        <a href="faqForm.php">
            <img src="images/faq icon.png" alt="FAQ Icon">
        </a>
    </div>

    <script>
        // Get all question elements
        const questions = document.querySelectorAll('.question');

        // Add click event listener to each question
        questions.forEach(question => {
            question.addEventListener('click', function() {
                // Find the answer element under current question
                const answer = this.nextElementSibling;

                // Toggle answer display
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    this.querySelector('.toggle-icon').textContent = '>';
                } else {
                    answer.style.display = 'block';
                    this.querySelector('.toggle-icon').textContent = 'v';
                }
            });
        });
    </script>
</body>
</html>
