<?php
include 'db_connect.php';

// 打开数据库连接
$conn = OpenCon();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 查询FAQ数据
$sql = "SELECT faqID, question, answer, email, severityQuestion, userID FROM faq";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// 关闭数据库连接
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png">
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
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='faq-item'>";
                    echo "<h2 class='question'>" . htmlspecialchars($row['question']) . "</h2>";
                    echo "<p class='answer'>" . htmlspecialchars($row['answer']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No FAQs found.</p>";
            }
            ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
