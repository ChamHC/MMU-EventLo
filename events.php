<?php
    // require 'db_connect.php';
    require_once 'trackRole.php';
    $userRole = checkUserRole();
    if ($userRole == null) {
        header('Location: index.php');
        exit();
    }
    else{
        $userId = $_SESSION['mySession'];
    }

    $conn = OpenCon();

    $sql = "SELECT * FROM event WHERE eventID IN (SELECT eventID FROM eventuser WHERE userID = $userId) AND eventDate > CURDATE()";
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
            $eventPicture = 'data:image/jpg;charset-utf8;base64,'.base64_encode($row['eventPicture']);
            $eventDescription = $row['eventDescription'];
            $hostId = $row['userID'];
            

            $sql2 = "SELECT * FROM user WHERE userID = $hostId LIMIT 1";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();
            $hostName = $row2['username'];

            $event = array(
                'eventId' => $eventId,
                'eventName' => $eventName,
                'eventFee' => $eventFee,
                'eventDate' => $newsDateFormatted = date('j F Y', strtotime($eventDate)),
                'eventTime' => $eventTime,
                'eventLocation' => $eventLocation,
                'eventCapacity' => $eventCapacity,
                'eventPicture' => $eventPicture,
                'eventDescription' => $eventDescription,
                'hostId' => $hostId,
                'hostName' => $hostName,
                'userId' => $userId
            );

            $events[] = $event;
        }
    }

    CloseCon($conn);

    function DisplayEvents($events, $userId, $userRole){
        foreach ($events as $event) {
            echo('
                <div class="event-container">
                    <img src="'.$event['eventPicture'].'" alt="Event Cover Image">
                    <div class="event-details">
                        <h2>'.$event['eventName'].'</h2><hr>
                        <p id="hostedBy">Hosted by <span id="host"> '.$event['hostName'].'</span></p>
                        <p>Date and Time: <span id="dateAndTime">'.$event['eventDate'].', '.$event['eventTime'].'</span></p>
                        <p>Venue: <span id="venue">'.$event['eventLocation'].'</span></p>
                        <p>Fee: <span id="fee">RM'.$event['eventFee'].'</span></p>
                        <p>Capacity: <span id="capacity">'.$event['eventCapacity'].'</span></p>
                        <p>Description: <br><span id="description">
                        '.$event['eventDescription'].'
                        </span></p>
                        <a href="events/leave_event.php?eventId='.$event['eventId'].'&userId='.$userId.'" ><button id="leaveButton">Leave</button>
                        <a href="announcement.php?id='.$event['eventId'].'" ><button id="announcementButton">Announcement</button></a>
                    </div>
                </div>
            ');
        }
        if ($userRole == 'Host'){
            echo ("
                <img id='AddEventsImgButton' src='images/add.png' alt='Add'>
                <div class='add-events'>
                    <hr><br>
                    <form action='events/add_events.php' method='post' enctype='multipart/form-data'>
                        <p>Title:<br><br><input type='text' name='eventsTitle' id='eventsTitle' required></p>
                        <p>Date:<br><br><input type='date' name='eventsDate' id='eventsDate' required></p>
                        <p>Time:<br><br><input type='time' name='eventsTime' id='eventsTime' required></p>
                        <p>Venue:<br><br><input type='text' name='eventsVenue' id='eventsVenue' required></p>
                        <p>Fee (RM):<br><br><input type='number' name='eventsFee' id='eventsFee' required></p>
                        <p>Capacity:<br><br><input type='number' name='eventsCapacity' id='eventsCapacity' required></p>
                        <p>Image:<br><br><input type='file' name='eventsImage' id='eventsImage' required></p>
                        <p>Description:<br><br><textarea name='eventsDescription' id='eventsDescription' required></textarea></p>
                        <input type='hidden' name='userId' value='".$userId."'>
                        <div class='button-container'>
                            <button id='createButton'>Create</button>
                            <button id='cancelButton'>Cancel</button>
                        </div>
                    </form>
                </div>  
            ");
            echo ("
                <script>
                    var addEventsImgButton = document.getElementById('AddEventsImgButton');
                    addEventsImgButton.onclick = function(){
                        var addEvents = document.getElementsByClassName('add-events')[0];
                        addEvents.style.display = addEvents.style.display == 'none' ? 'block' : 'none';
                    }
                    var cancelButton = document.getElementsByClassName('add-events')[0].getElementsByTagName('button')[1];
                    cancelButton.onclick = function(){
                        event.preventDefault();
                        var addEvents = document.getElementsByClassName('add-events')[0];
                        addEvents.style.display = 'none';
                    }
                </script>
            ");
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
    <link rel="stylesheet" href="css/eventsStyle.css">
    <title>Events</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon"><hr>
        <script>
            var searchBar = document.getElementById('searchBar');
            searchBar.addEventListener('keyup', function(event){
                var searchValue = event.target.value.toLowerCase();
                var eventContainers = document.getElementsByClassName('event-container');
                
                Array.from(eventContainers).forEach(function(eventContainer){
                    var eventName = eventContainer.getElementsByTagName('h2')[0].innerText;
                    var eventHost = eventContainer.getElementsByTagName('span')[0].innerText;
                    var eventDateAndTime = eventContainer.getElementsByTagName('span')[1].innerText;
                    var eventVenue = eventContainer.getElementsByTagName('span')[2].innerText;
                    var eventFee = eventContainer.getElementsByTagName('span')[3].innerText;
                    var eventCapacity = eventContainer.getElementsByTagName('span')[4].innerText;
                    var eventDescription = eventContainer.getElementsByTagName('span')[5].innerText;

                    if(eventName.toLowerCase().indexOf(searchValue) != -1 || 
                        eventHost.toLowerCase().indexOf(searchValue) != -1 || 
                        eventDateAndTime.toLowerCase().indexOf(searchValue) != -1 || 
                        eventVenue.toLowerCase().indexOf(searchValue) != -1 || 
                        eventFee.toLowerCase().indexOf(searchValue) != -1 || 
                        eventCapacity.toLowerCase().indexOf(searchValue) != -1 || 
                        eventDescription.toLowerCase().indexOf(searchValue) != -1){
                        eventContainer.style.display = 'flex';
                    } else {
                        eventContainer.style.display = 'none';
                    }
                });
            });
        </script>
    </div>
    <div class="main">
        <?php
            DisplayEvents($events, $userId, $userRole);
        ?>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>