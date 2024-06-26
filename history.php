<?php
// Check user role and session
require_once 'trackRole.php';
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: index.php');
    exit();
} else {
    $userId = $_SESSION['mySession']; // Assuming 'mySession' contains the user ID after login
}

// Open database connection
$conn = OpenCon();

// Get current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Query to fetch past events attended by the user
$sql = "SELECT e.eventID, e.eventName, e.eventFee, e.eventDate, e.eventTime, e.eventLocation, e.eventCapacity, e.eventPicture, e.eventDescription
        FROM event e
        INNER JOIN eventuser eu ON e.eventID = eu.eventID
        WHERE eu.userID = ? AND CONCAT(e.eventDate, ' ', e.eventTime) < ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// Bind parameters and execute query
$stmt->bind_param("ss", $userId, $currentDateTime);
if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}

// Get result set
$result = $stmt->get_result();
$events = array();

// Fetch and process each row from the result set
while ($row = $result->fetch_assoc()) {
    // Prepare event data for display
    $event = array(
        'eventId' => $row['eventID'],
        'eventName' => htmlspecialchars($row['eventName']),
        'eventFee' => htmlspecialchars($row['eventFee']),
        'eventDate' => date('j F Y', strtotime($row['eventDate'])),
        'eventTime' => htmlspecialchars($row['eventTime']),
        'eventLocation' => htmlspecialchars($row['eventLocation']),
        'eventCapacity' => htmlspecialchars($row['eventCapacity']),
        'eventPicture' => 'data:image/jpeg;base64,' . base64_encode($row['eventPicture']), // Convert picture to base64 for display
        'eventDescription' => htmlspecialchars($row['eventDescription'])
    );
    // Add event data to events array
    $events[] = $event;
}

// Close statement and database connection
$stmt->close();
CloseCon($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Participation History</title>
    <!-- Include necessary CSS -->
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/history.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <h1>Event Participation History</h1>
        <hr>
        <?php if (empty($events)): ?>
            <p>No past events found.</p>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
                <div class="event-container">
                    <img src="<?= $event['eventPicture'] ?>" alt="Event Image">
                    <div class="event-details">
                        <h2><?= $event['eventName'] ?></h2>
                        <p>Date: <?= $event['eventDate'] ?></p>
                        <p>Time: <?= $event['eventTime'] ?></p>
                        <p>Location: <?= $event['eventLocation'] ?></p>
                        <p>Fee: <?= $event['eventFee'] ?></p>
                        <p>Description: <?= $event['eventDescription'] ?></p>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
