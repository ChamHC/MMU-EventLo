<?php
    require '../db_connect.php';
    $conn = OpenCon();

    if (empty(trim($_POST['newsTitle'])) || empty(trim($_POST['newsDescription']))) {
        echo("
            <script>
                alert('Please fill in all fields');
                window.location.href = '../manager.php?page=newsManager';
            </script>
        ");
    }

    $newsTitle = $_POST['newsTitle'];
    $newsDescription = $_POST['newsDescription'];
    $newsDate = date('Y-m-d');
    $userId = 1;

    $query = "INSERT INTO news (newsName, newsDescription, newsDate, userID) VALUES ('$newsTitle', '$newsDescription', '$newsDate', '$userId')";
    $result = $conn->query($query);

    CloseCon($conn);

    if ($result) {
        header("Location: ../manager.php?page=newsManager");
    } else {
        echo "<script>alert('Add failed');</script>";
    }
?>