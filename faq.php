<?php
include 'db_connect.php';

// Open database connection
$conn = OpenCon();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch FAQ data
$sql = "SELECT faqID, question, answer FROM faq";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Prepare array to hold FAQs
$faqs = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $faqId = $row['faqID'];
        $question = htmlspecialchars($row['question']);
        $answer = htmlspecialchars($row['answer']);

        $faq = array(
            'faqId' => $faqId,
            'question' => $question,
            'answer' => $answer
        );

        $faqs[] = $faq;
    }
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
    <style>
        .answer {
            display: none; /* Initially hide the answer */
        }
        .question {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toggle-icon {
            margin-left: 10px; /* Adjust icon position */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="main">
        <h1 class="title">Frequently Asked Questions (FAQ)</h1>
        <div class="faq-container">
            <?php
            if (empty($faqs)) {
                echo "<p>No FAQs found.</p>";
            } else {
                foreach ($faqs as $faq) {
                    echo "<div class='faq-item'>";
                    echo "<h2 class='question'>" . $faq['question'] . "<span class='toggle-icon'>></span></h2>";
                    echo "<p class='answer'>" . $faq['answer'] . "</p>";
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
