<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $eventId = $_GET['eventId'];
    $userId = $_GET['userId'];

    $sql = "DELETE FROM eventuser WHERE eventID = $eventId AND userID = $userId";
    $conn->query($sql);

    CloseCon($conn);

    header("Location: ../events.php");
?>