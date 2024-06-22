<?php
require '../db_connect.php';
$conn = OpenCon();

$announcementID = $_POST['announcementId'];
$announcementName = $_POST['announcementTitle'];
$announcementDescription = $_POST['announcementDescription'];
$announcementDate = date('Y-m-d');
$eventID = $_POST['eventId'];
$userID = $_SESSION['mySession']; // Assuming you have the session started and user ID stored in session

$query = "UPDATE announcement SET announcementName = ?, announcementDescription = ?, announcementDate = ?, eventID = ?, userID = ? WHERE announcementID = ?";
$statement = $conn->prepare($query);
$statement->bind_param("sssiii", $announcementName, $announcementDescription, $announcementDate, $eventID, $userID, $announcementID);
$result = $statement->execute();

CloseCon($conn);

if ($result) {
    header("Location: ../announcement.php?id=$eventID");
    exit();
} else {
    echo "<script>alert('Update failed');</script>";
}
?>
