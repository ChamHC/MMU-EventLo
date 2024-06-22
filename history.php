<?php
// Ensure the session is started (already handled in trackRole.php)
require_once 'trackRole.php';

// Ensure user is authenticated
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: index.php');
    exit();
}

// Assuming $_SESSION['mySession'] contains the logged-in user's ID
$userId = $_SESSION['mySession'];

// Connect to database and fetch events
$conn = OpenCon();

// Query to fetch events attended by the user
$sql = "SELECT e.eventID, e.eventName, e.eventFee, e.eventDate, e.eventTime, e.eventLocation, e.eventCapacity, e.eventPicture, e.eventDescription
        FROM event e
        INNER JOIN eventuser eu ON e.eventID = eu.eventID
        WHERE eu.userID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$events = array();

while ($row = $result->fetch_assoc()) {
    // Fetch event details
    $eventId = $row['eventID'];
    $eventName = $row['eventName'];
    $eventFee = $row['eventFee'];
    $eventDate = date('j F Y', strtotime($row['eventDate']));
    $eventTime = $row['eventTime'];
    $eventLocation = $row['eventLocation'];
    $eventCapacity = $row['eventCapacity'];
    $eventPicture = 'data:image/jpeg;base64,' . base64_encode($row['eventPicture']);
    $eventDescription = $row['eventDescription'];

    // Create event array
    $event = array(
        'eventId' => $eventId,
        'eventName' => $eventName,
        'eventFee' => $eventFee,
        'eventDate' => $eventDate,
        'eventTime' => $eventTime,
        'eventLocation' => $eventLocation,
        'eventCapacity' => $eventCapacity,
        'eventPicture' => $eventPicture,
        'eventDescription' => $eventDescription
    );

    // Push event into events array
    $events[] = $event;
}

// Close statement and connection
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
    <style>
        /* Ensure main content area is properly spaced */
        .main {
            padding-top: 20px; /* Adjust as needed */
            padding-bottom: 20px; /* Adjust as needed */
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="main">
        <h1>Event Participation History</h1>
        <hr>
        <?php
            // Display events fetched from database
            foreach ($events as $event) {
                // Output event details
                echo '
                    <div class="event-container">
                        <img src="'.$event['eventPicture'].'" alt="Event Image">
                        <div class="event-details">
                            <h2>'.$event['eventName'].'</h2>
                            <p>Date: '.$event['eventDate'].'</p>
                            <p>Time: '.$event['eventTime'].'</p>
                            <p>Location: '.$event['eventLocation'].'</p>
                            <p>Fee: '.$event['eventFee'].'</p>
                            <p>Description: '.$event['eventDescription'].'</p>
                        </div>
                    </div>
                    <hr>
                ';
            }
        ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
