
<?php
require 'db_connect.php';
$conn = OpenCon();

$userId = 9;

// Get the current date
$currentDate = date('Y-m-d');

// SQL query to get upcoming events that the user hasn't registered for
$sql = "
    SELECT * FROM event 
    WHERE eventDate > '$currentDate' 
    AND eventID NOT IN (SELECT eventID FROM eventuser WHERE userID = $userId)
";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventId = $row['eventID'];
        $eventName = $row['eventName'];
        $eventFee = $row['eventFee'];
        $eventDate = $row['eventDate'];
        $eventTime = $row['eventTime'];
        $eventLocation = $row['eventLocation'];
        $eventCapacity = $row['eventCapacity'];
        $eventPicture = 'data:image/jpeg;base64,'.base64_encode($row['eventPicture']);
        $eventDescription = $row['eventDescription'];
        $hostId = $row['userID'];
        
        // Fetch the host's name
        $sql2 = "SELECT * FROM user WHERE userID = $hostId LIMIT 1";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $hostName = $row2['username'];

        $event = array(
            'eventId' => $eventId,
            'eventName' => $eventName,
            'eventFee' => $eventFee,
            'eventDate' => date('j F Y', strtotime($eventDate)),
            'eventTime' => $eventTime,
            'eventLocation' => $eventLocation,
            'eventCapacity' => $eventCapacity,
            'eventPicture' => $eventPicture,
            'eventDescription' => $eventDescription,
            'hostId' => $hostId,
            'hostName' => $hostName
        );

        $events[] = $event;
    }
}

CloseCon($conn);

function DisplayEvents($events, $userId){
    $count = 0;
    echo('<div class="catalogue-pair">');
    foreach ($events as $event) {
        if ($count % 2 == 0 && $count != 0) {
            echo('</div><div class="catalogue-pair">');
        }
        
        $containerClass = ($count % 2 == 0) ? 'left-catalogue-container' : 'right-catalogue-container';
        echo('
            <div class="'.$containerClass.'">
                <div class="event-item">
                    <img src="'.$event['eventPicture'].'" alt="Event Cover Image" class="center">
                    <div class="event-details">
                        <h2>'.$event['eventName'].'</h2>
                        <p id="hostedBy">Hosted by <span id="host">'.$event['hostName'].'</span></p>
                        <button class="eventDetailsButton" onclick="navigateToCatalogueDetailsPage('.$event['eventId'].', '.$userId.')">Details</button>
                    </div>
                </div>
            </div>
        ');
        
        $count++;
    }
    echo('</div>');
    echo('</br>');

    if (empty($events)) {
        echo '<p class="no-events-message">No upcoming events that you have not joined.</p>';
    }
}
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
    <link rel="stylesheet" href="css/catalogueStyle.css">
    <title>E-Catalogue</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon" onclick="searchEvents()">
        <hr>
    </div>
    <div class="main-content">
        <?php DisplayEvents($events, $userId); ?>
    </div>
    <?php include 'footer.php' ?>
    <script>
        function navigateToCatalogueDetailsPage(eventId, userId) {
            window.location.href = "catalogueDetails.php?eventId=" + eventId + "&userId=" + userId; 
        }
    </script>
</body>
</html>
