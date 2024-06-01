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
    <script src="js/events.js"></script>
    <title>Events</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon" onclick="searchEvents()"><hr>
    </div>
    <div class="main">
        <div class="event-container">
            <img src="images/logo.png" alt="Event Cover Image">
            <div class="event-details">
                <h2>Event Name</h2><hr>
                <p id="hostedBy">Hosted by <span id="host"> Write the name of host here</span></p>
                <p>Date and Time: <span id="dateAndTime">Write date and time of event here</span></p>
                <p>Venue: <span id="venue">Write the venue of event here</span></p>
                <p>Fee: <span id="fee">Write fee to participate here</span></p>
                <p>Capacity: <span id="capacity">Write participant capacity here</span></p>
                <p>Description: <br><span id="description">
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                </span></p>
                <button id="leaveButton" onclick="">Leave</button>
                <button id="announcementButton" onclick="">Announcement</button>
            </div>
        </div>
        <div class="event-container">
            <img src="images/logo.png" alt="Event Cover Image">
            <div class="event-details">
                <h2>Event Name</h2><hr>
                <p id="hostedBy">Hosted by <span id="host"> Write the name of host here</span></p>
                <p>Date and Time: <span id="dateAndTime">Write date and time of event here</span></p>
                <p>Venue: <span id="venue">Write the venue of event here</span></p>
                <p>Fee: <span id="fee">Write fee to participate here</span></p>
                <p>Capacity: <span id="capacity">Write participant capacity here</span></p>
                <p>Description: <br><span id="description">
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                </span></p>
                <button id="leaveButton" onclick="">Leave</button>
                <button id="announcementButton" onclick="">Announcement</button>
            </div>
        </div>
        <div class="event-container">
            <img src="images/logo.png" alt="Event Cover Image">
            <div class="event-details">
                <h2>Event Name</h2><hr>
                <p id="hostedBy">Hosted by <span id="host"> Write the name of host here</span></p>
                <p>Date and Time: <span id="dateAndTime">Write date and time of event here</span></p>
                <p>Venue: <span id="venue">Write the venue of event here</span></p>
                <p>Fee: <span id="fee">Write fee to participate here</span></p>
                <p>Capacity: <span id="capacity">Write participant capacity here</span></p>
                <p>Description: <br><span id="description">
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                    write description here write description here write description here write description here
                </span></p>
                <button id="leaveButton" onclick="">Leave</button>
                <button id="announcementButton" onclick="">Announcement</button>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>