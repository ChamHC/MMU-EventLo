<?php
require_once 'trackRole.php';
$userRole = checkUserRole();
if ($userRole == null) {
    header('Location: login.php');
    exit();
} else {
    $userId = $_SESSION['mySession'];
}

$conn = OpenCon();

// Retrieve the eventID from the URL
if (!isset($_GET['id'])) {
    die("Event ID is missing");
}
$eventId = $_GET['id'];

// SQL query to get announcements for the specific event
$sql = "SELECT * FROM announcement WHERE eventID = $eventId";
$result = $conn->query($sql);

$announcements = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcement = array(
            'announcementId' => $row['announcementID'],
            'announcementName' => $row['announcementName'],
            'announcementDate' => $row['announcementDate'],
            'announcementDescription' => $row['announcementDescription'],
            'userId' => $row['userID']  // Ensure the case matches here
        );

        // Get the username of the host
        $hostId = $row['userID'];
        $sql2 = "SELECT username FROM user WHERE userID = $hostId LIMIT 1";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $hostName = $row2['username'];

        $announcement['hostName'] = $hostName;
        $announcements[] = $announcement;
    }
}

function DisplayAnnouncements($announcements, $userRole, $userId, $eventId, $conn) {
    if (empty($announcements)) {
        // Check if the current user is the host of the event
        $sql = "SELECT userID FROM event WHERE eventID = $eventId";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $eventHostId = $row['userID'];

        if ($userId == $eventHostId) {
            // Display a message or perform an action for the host
            echo '<div class="no-announcements-container">';
            echo '<p class="no-announcements-message">No announcements for this event. You can create one!</p>';
            echo '</div>';
        } else {
            // Display a message or perform an action for non-host users
            echo '<div class="no-announcements-container">';
            echo '<p class="no-announcements-message">No announcements for this event.</p>';
            echo '</div>';
        }
    } else {
        foreach ($announcements as $announcement) {
            echo('
            <div class="announcement-container">
                <div class="event-details">
                    <h2>'.$announcement['announcementName'].'</h2><hr>
                    <p id="hostedBy">Hosted by <span id="host">'.$announcement['hostName'].'</span></p>
                    <p><span id="description">'.$announcement['announcementDescription'].'</span></p>
                </div>
                <div class="content-bottom">
                    <div class="button-container">');
                    if ($userRole == 'Host' && $announcement['userId'] == $userId) {
                        echo('
                        <img id="EditImgButton" src="images/edit.png" alt="Edit" onclick="openEditPanel('.$announcement['announcementId'].', \''.$announcement['announcementName'].'\', \''.$announcement['announcementDescription'].'\')">
                        <img id="DeleteImgButton" src="images/delete.png" alt="Delete" onclick="showConfirmationPanel('.$announcement['announcementId'].')">
                        ');
                    }
                    echo('</div>
                    <p id="datePost">Date: <span id="date">'.date('j F Y', strtotime($announcement['announcementDate'])).'</span></p>
                </div>
            </div>
            ');
        }
    }
}


// Fetch the event details to determine the host ID
$sqlEvent = "SELECT userID FROM event WHERE eventID = $eventId";
$resultEvent = $conn->query($sqlEvent);
$rowEvent = $resultEvent->fetch_assoc();
$eventHostId = $rowEvent['userID'];

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
    <link rel="stylesheet" href="css/announcementStyle.css">
    <title>Announcement</title>
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
    var announcenementContainers = document.getElementsByClassName('announcement-container');

    Array.from(announcenementContainers).forEach(function(announcementContainer){
        var announcementName = announcementContainer.querySelector('h2').innerText.toLowerCase();
        var announcementDescription = announcementContainer.querySelector('span#description').innerText.toLowerCase();

        if(announcementName.includes(searchValue) || announcementDescription.includes(searchValue)){
            announcementContainer.style.display = 'flex';
        } else {
            announcementContainer.style.display = 'none';
        }
        });
    });

    </script>
    </div>

    <div class="main">
        <?php DisplayAnnouncements($announcements, $userRole, $userId, $eventId, $conn); ?>
        <?php if ($userRole == 'Host' && $userId == $eventHostId) { ?>
        <div class="add-button-container">
            <img src="images/add.png" alt="Add" id="Add" onclick="openAnnouncementPanel()">
        </div>
        <?php } ?>

        <div class="confirmation-panel-outer" id="confirmationOuter">
            <div class="confirmation-panel-inner" id="confirmationInner">
                <p>Are you sure you want to delete this announcement?</p>
                <form id="deleteForm" action="/MMU-EventLo/host/delete_announcementHost.php?id=$announcement[announcementId]&eventId=$event[eventId]" method="GET">
                    <input type="hidden" id="deleteAnnouncementId" name="id">
                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                    <button type="submit">Delete</button>
                    <button type="button" onclick="hideConfirmationPanel()">Cancel</button>
                </form>
            </div>
        </div>

        <div class="announcement-panel" id="uploadAnnouncementPanel">
            <div class="announcement-panel-inner">
                <div class="close-button" onclick="closeAnnouncementPanel()">&times;</div>
                <form id="announcementUploadForm" action="/MMU-EventLo/host/upload_announcementHost.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                    <label for="announcementName">Title:</label>
                    <input type="text" id="announcementName" name="announcementName" required>
                    <label for="announcementDescription">Description:</label>
                    <textarea id="announcementDescription" name="announcementDescription" rows="4" required></textarea>
                <div class="button-container">
                    <button type="submit">Upload</button>
                </div>
                </form>
            </div>
        </div>

        <div class="announcement-panel" id="editAnnouncementPanel">
            <div class="announcement-panel-inner">
                <div class="close-button" onclick="closeEditPanel()">&times;</div>
        <form id="announcementEditForm" action="/MMU-EventLo/host/edit_announcementHost.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="announcementId" id="editAnnouncementId">
            <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
            <label for="editAnnouncementName">Title:</label>
            <input type="text" id="editAnnouncementName" name="announcementTitle" required>
            <label for="editAnnouncementDescription">Description:</label>
            <textarea id="editAnnouncementDescription" name="announcementDescription" rows="4" required></textarea>
            <div class="button-container">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>


    </div>

    <?php include 'footer.php' ?>

    <script>
    function openAnnouncementPanel() {
        document.getElementById('uploadAnnouncementPanel').style.display = 'block';
    }

    function closeAnnouncementPanel() {
        document.getElementById('uploadAnnouncementPanel').style.display = 'none';
    }

    function openEditPanel(announcementId, announcementName, announcementDescription) {
        document.getElementById('editAnnouncementId').value = announcementId;
        document.getElementById('editAnnouncementName').value = announcementName;
        document.getElementById('editAnnouncementDescription').value = announcementDescription;
        document.getElementById('editAnnouncementPanel').style.display = 'block';
    }

    function closeEditPanel() {
        document.getElementById('editAnnouncementPanel').style.display = 'none';
    }

    function showConfirmationPanel(announcementId) {
        document.getElementById('confirmationOuter').style.display = 'flex';
        document.getElementById('deleteAnnouncementId').value = announcementId;
    }

    function hideConfirmationPanel() {
        document.getElementById('confirmationOuter').style.display = 'none';
    }
</script>

</body>
</html>
