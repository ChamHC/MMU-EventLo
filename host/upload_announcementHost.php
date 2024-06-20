<?php
require '../db_connect.php';
$conn = OpenCon();

$announcementName = $_POST['announcementName'];
$announcementDescription = $_POST['announcementDescription'];
$announcementDate = date('Y-m-d');
$eventID = $_POST['eventId'];
$userID = $_POST['userId'];

$query = "INSERT INTO announcement (announcementName, announcementDescription, announcementDate, eventID, userID) VALUES (?, ?, ?, ?, ?)";
$statement = $conn->prepare($query);
$statement->bind_param("sssii", $announcementName, $announcementDescription, $announcementDate, $eventID, $userID);
$result = $statement->execute();

CloseCon($conn);

if ($result) {
    header("Location: ../announcement.php?id=$eventID");
} else {
    echo "<script>alert('Upload failed');</script>";
}
?>
