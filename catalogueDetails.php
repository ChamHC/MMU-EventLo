
<?php
require 'db_connect.php';
$conn = OpenCon();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['joinEvent'])) {
    if (!isset($_POST['userId']) || !isset($_POST['eventId'])) {
        die("User ID or Event ID is missing");
    }
    
    $userId = $_POST['userId'];
    $eventId = $_POST['eventId'];

    // Insert into eventuser table
    $sql = "INSERT INTO eventuser (userID, eventID) VALUES ($userId, $eventId)";
    if ($conn->query($sql) === TRUE) {
        $message = "Successfully joined the event.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Retrieve the eventID from the URL
if (!isset($_GET['eventId'])) {
    die("Event ID is missing");
}
$eventId = $_GET['eventId'];

$sql = "SELECT * FROM event WHERE eventID = $eventId";
$result = $conn->query($sql);

if ($result === false) {
    echo "Error: " . $conn->error;
} elseif ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $eventName = $row['eventName'];
    $eventFee = $row['eventFee'];
    $eventDate = $row['eventDate'];
    $eventTime = $row['eventTime'];
    $eventLocation = $row['eventLocation'];
    $eventCapacity = $row['eventCapacity'];
    $eventPicture = 'data:image/jpeg;base64,' . base64_encode($row['eventPicture']);
    $eventDescription = $row['eventDescription'];
    $hostId = $row['userID'];

    $sql2 = "SELECT * FROM user WHERE userID = $hostId LIMIT 1";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
    $hostName = $row2['username'];

    // Display the event details
    $eventDetails = '
        <div class="event-container">
            <img src="'.$eventPicture.'" alt="Event Cover Image">
            <div class="event-details">
                <h2>'.$eventName.'</h2><hr>
                <p id="hostedBy">Hosted by <span id="host">'.$hostName.'</span></p>
                <p>Date and Time: <span id="dateAndTime">'.$eventDate.', '.$eventTime.'</span></p>
                <p>Venue: <span id="venue">'.$eventLocation.'</span></p>
                <p>Fee: <span id="fee">RM'.$eventFee.'</span></p>
                <p>Capacity: <span id="capacity">'.$eventCapacity.'</span></p>
                <p>Description: <br><span id="description">'.$eventDescription.'</span></p>
                <form method="post" action="">
                    <input type="hidden" name="userId" value="'.$userId.'">
                    <input type="hidden" name="eventId" value="'.$eventId.'">
                    <button type="submit" name="joinEvent">Join</button>
                </form>
            </div>
        </div>
    ';
} else {
    $eventDetails = "Event not found";
}

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
    <link rel="stylesheet" href="css/searchStyle.css">
    <link rel="stylesheet" href="css/catalogueDetailsStyle.css">
    <title>E-Catalogue Details</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon" onclick="searchEvents()">
    </div>
    <div class="main-content">
        <?php 
        if (isset($message)) {
            echo '<p>' . $message . '</p>';
        }
        echo $eventDetails; 
        ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>