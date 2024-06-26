<?php
    if (!isset($_GET['page'])) header('Location: manager.php?page=newsManager');

    // require 'db_connect.php';
    require_once 'trackRole.php';
    $userRole = checkUserRole();
    if ($userRole != 'Admin') {
        header('Location: index.php');
        exit();
    }

    $conn = OpenCon();

    if ($_GET['page'] == 'newsManager'){
        $news_sql = "SELECT * FROM news";
        $news_result = $conn->query($news_sql);
        $news = array();
        if ($news_result->num_rows > 0) {
            while ($row = $news_result->fetch_assoc()) {
                $newsId = $row['newsID'];
                $newsName = $row['newsName'];
                $newsDate = $row['newsDate'];
                $newsDescription = nl2br($row['newsDescription']);
                $adminId = $row['userID'];
    
                $news_sql_2 = "SELECT * FROM user WHERE userID = $adminId LIMIT 1";
                $news_result_2 = $conn->query($news_sql_2);
                $row_2 = $news_result_2->fetch_assoc();
                $adminName = $row_2['username'];
    
                $new = array(
                    'newsId' => $newsId,
                    'newsName' => $newsName,
                    'newsDate' => $newsDateFormatted = date('j F Y', strtotime($newsDate)),
                    'newsDescription' => $newsDescription,
                    'adminId' => $adminId,
                    'adminName' => $adminName
                );
    
                $news[] = $new;
            }
        }
    }

    if ($_GET['page'] == 'roleManager'){
        $role_sql = "SELECT * FROM user WHERE role = 'User' AND applyHost = 1";
        $role_result = $conn->query($role_sql);
        $roles = array();
        if ($role_result->num_rows > 0) {
            while ($row = $role_result->fetch_assoc()) {
                $username = $row['username'];
                $userId = $row['userID'];
                $contactNum = $row['contactNum'];
                $email = $row['email'];

                $role = array(
                    'username' => $username,
                    'userId' => $userId,
                    'contactNum' => $contactNum,
                    'email' => $email,
                );

                $roles[] = $role;
            }
        }
    }

    if ($_GET['page'] == 'eventManager'){
        $event_sql = "SELECT * FROM event";
        $event_result = $conn->query($event_sql);
        $events = array();
        if ($event_result->num_rows > 0) {
            while ($row = $event_result->fetch_assoc()) {
                $eventId = $row['eventID'];
                $eventName = $row['eventName'];
                $eventFee = $row['eventFee'];
                $eventDate = $row['eventDate'];
                $eventTime = $row['eventTime'];
                $eventLocation = $row['eventLocation'];
                $eventCapacity = $row['eventCapacity'];
                $eventPicture = base64_encode($row['eventPicture']);
                $eventDescription = $row['eventDescription'];
                $hostId = $row['userID'];

                $event_sql_2 = "SELECT * FROM user WHERE userID = $hostId LIMIT 1";
                $event_result_2 = $conn->query($event_sql_2);
                $row_2 = $event_result_2->fetch_assoc();
                $hostName = $row_2['username'];

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
                    'hostName' => $hostName
                );

                $events[] = $event;
            }
        }
    }

    if (isset($_GET['id'])) {
        $eventId = $_GET['id'];
        $event_sql = "SELECT * FROM event WHERE eventID = $eventId LIMIT 1";
        $event_result = $conn->query($event_sql);
        $row = $event_result->fetch_assoc();
        $eventName = $row['eventName'];
        $eventFee = $row['eventFee'];
        $eventDate = $row['eventDate'];
        $eventTime = $row['eventTime'];
        $eventLocation = $row['eventLocation'];
        $eventCapacity = $row['eventCapacity'];
        $eventPicture = 'data:image/jpeg;base64,'.base64_encode($row['eventPicture']);
        $eventDescription = nl2br($row['eventDescription']);
        $hostId = $row['userID'];

        $event_sql_2 = "SELECT * FROM user WHERE userID = $hostId LIMIT 1";
        $event_result_2 = $conn->query($event_sql_2);
        $row_2 = $event_result_2->fetch_assoc();
        $hostName = $row_2['username'];

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
            'hostName' => $hostName
        );

        $announcement_sql = "SELECT * FROM announcement WHERE eventID = $eventId";
        $announcement_result = $conn->query($announcement_sql);
        $announcements = array();
        if ($announcement_result->num_rows > 0) {
            while ($row = $announcement_result->fetch_assoc()) {
                $announcementId = $row['announcementID'];
                $announcementName = $row['announcementName'];
                $announcementDescription = nl2br($row['announcementDescription']);
                $announcementDate = $row['announcementDate'];

                $announcement = array(
                    'announcementId' => $announcementId,
                    'announcementName' => $announcementName,
                    'announcementDate' => $announcementDateFormatted = date('j F Y', strtotime($announcementDate)),
                    'announcementDescription' => $announcementDescription,
                );

                $announcements[] = $announcement;
            }
        }
    }

    if ($_GET['page'] == 'faqManager'){
        $faq_sql = "SELECT * FROM faq";
        $faq_result = $conn->query($faq_sql);
        $faqs = array();
        if ($faq_result->num_rows > 0) {
            while ($row = $faq_result->fetch_assoc()) {
                $faqId = $row['faqID'];
                $faqQuestion = $row['question'];
                $faqAnswer = nl2br($row['answer']);
                $faqSeverity = $row['severityQuestion'];
                $userId = $row['userID'];

                $faq_sql_2 = "SELECT * FROM user WHERE userID = $userId LIMIT 1";
                $faq_result_2 = $conn->query($faq_sql_2);
                $row_2 = $faq_result_2->fetch_assoc();
                $username = $row_2['username'];

                $faq = array(
                    'faqId' => $faqId,
                    'faqQuestion' => $faqQuestion,
                    'faqAnswer' => $faqAnswer,
                    'faqSeverity' => $faqSeverity,
                    'userId' => $userId,
                    'username' => $username
                );

                $faqs[] = $faq;
            }
        }
    }

    CloseCon($conn);

    function displayNews($news){
        foreach ($news as $new){
            echo ("
                <div class='content-block'>
                    <h2>".$new['newsName']."</h2><hr>
                    <div class='content-main'>
                        <p id='Description'>
                            ".$new['newsDescription']."
                        </p> 
                        <div class='content-bottom'>
                            <p id='ContentDetails'>
                                Date: ".$new['newsDate']."<br>
                                Posted by ".$new['adminName']."
                            </p>
                            <div class='button-container'>
                                <img id='EditImgButton' src='images/edit.png' alt='Edit'>
                                <a href='manager/delete_news.php?id=$new[newsId]'><img id='DeleteImgButton' src='images/delete.png' alt='Delete'></a>
                            </div>
                        </div>
                    </div>
                    <div class='edit-news'>
                        <hr><br>
                        <form action='manager/edit_news.php' method='post'>
                            <input type='hidden' name='newsId' value='".$new['newsId']."'>
                            <p>Title:<br><br><input type='text' name='newsTitle' id='newsTitle' value='".$new['newsName']."'></p>
                            <p>Description:<br><br><textarea name='newsDescription' id='newsDescription'>".str_replace(array('<br />', '<br>', '<br/>'), '', $new['newsDescription'])."</textarea></p>
                            <div class='button-container'>
                                <button id='editButton'>Confirm</button>
                                <button id='cancelButton'>Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            ");
        }
        echo ("
            <img id='AddNewsImgButton' src='images/add.png' alt='Add'>
            <div class='add-news'>
                <hr><br>
                <form action='manager/add_news.php' method='post'>
                    <p>Title:<br><br><input type='text' name='newsTitle' id='newsTitle'></p>
                    <p>Description:<br><br><textarea name='newsDescription' id='newsDescription'></textarea></p>
                    <div class='button-container'>
                        <button id='createButton'>Create</button>
                        <button id='cancelButton'>Cancel</button>
                    </div>
                </form>
            </div>  
        ");

        echo ("
            <script>
                var contentBlocks = document.getElementsByClassName('content-block');

                for (let i = 0; i < contentBlocks.length; i++) {
                    var editImgButton = contentBlocks[i].getElementsByTagName('img')[0];
                    editImgButton.onclick = function(){
                        var editNews = contentBlocks[i].getElementsByClassName('edit-news')[0];
                        editNews.style.display = editNews.style.display == 'none' ? 'block' : 'none';
                    }

                    var cancelButton = contentBlocks[i].getElementsByTagName('button')[1];
                    cancelButton.onclick = function(){
                        event.preventDefault();
                        var editNews = contentBlocks[i].getElementsByClassName('edit-news')[0];
                        editNews.style.display = 'none';
                    }
                }

                var addNewsImgButton = document.getElementById('AddNewsImgButton');
                addNewsImgButton.onclick = function(){
                    var addNews = document.getElementsByClassName('add-news')[0];
                    addNews.style.display = addNews.style.display == 'none' ? 'block' : 'none';
                }
                var cancelButton = document.getElementsByClassName('add-news')[0].getElementsByTagName('button')[1];
                cancelButton.onclick = function(){
                    event.preventDefault();
                    var addNews = document.getElementsByClassName('add-news')[0];
                    addNews.style.display = 'none';
                }
            </script>
        ");
    }

    function displayRole($roles){
        echo ("
            <div class='content-block'>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>User ID</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
        ");
        foreach($roles as $role){
            echo ("
                <tr>
                    <td>$role[username]</td>
                    <td>$role[userId]</td>
                    <td>$role[contactNum]</td>
                    <td>$role[email]</td>
                    <td>
                        <a href='manager/manage_role.php?id=$role[userId]&action=approve'><button id='ApproveButton'>Approve</button></a>
                        <a href='manager/manage_role.php?id=$role[userId]&action=reject'><button id='RejectButton'>Reject</button></a>
                    </td>
                </tr>
            ");
        }
        echo ("
                </table>
            </div>
        ");
    }

    function displayEvent($events){
        echo ("
            <div class='content-block'>
                <table>
                    <tr>
                        <th>Event</th>
                        <th>Host</th>
                        <th>Venue</th>
                        <th>Date and Time</th>
                        <th>Fee</th>
                        <th>Actions</th>
                    </tr>"
        );
        foreach($events as $event){
            echo("
                <tr>
                    <td>".$event['eventName']."</td>
                    <td>".$event['hostName']."</td>
                    <td>".$event['eventLocation']."</td>
                    <td>".$event['eventDate'].", ".$event['eventTime']."</td>
                    <td>RM".$event['eventFee']."</td>
                    <td>
                        <a href='manager.php?page=announcementManager&id=".$event['eventId']."'><button id='ManageButton'>Manage</button></a>
                        <a href='manager/remove_event.php?id=$event[eventId]'><button id='RemoveButton'>Remove</button></a>
                    </td>
                </tr>
            ");
        }
        echo ("
                </table>
            </div>
        ");
    }

    function displayAnnouncement($event, $announcements){
        echo("
            <div class='content-block'>
                <div class='event-container' style='border: none'>
                    <img src='$event[eventPicture]' alt='Event Cover Image'>
                    <div class='event-details'>
                        <h2>$event[eventName]</h2><hr>
                        <p id='hostedBy'>Hosted by <span id='host'> $event[hostName]</span></p>
                        <p>Date and Time: <span id='dateAndTime'>$event[eventDate], $event[eventTime]</span></p>
                        <p>Venue: <span id='venue'>$event[eventLocation]</span></p>
                        <p>Fee: <span id='fee'>RM$event[eventFee]</span></p>
                        <p>Capacity: <span id='capacity'>$event[eventCapacity]</span></p>
                        <p>Description: <br><span id='description'>
                        $event[eventDescription]
                        </span></p>
                    </div>
                </div>
                <div class='announcements-container'>
                    <h2>Announcements</h2>
        ");
        foreach ($announcements as $announcement){
            echo("
                <div class='announcement'>
                    <h3 id='Title'>$announcement[announcementName]</h3>
                    <p id='Details'>$announcement[announcementDescription]</p>
                    <div class='content-bottom'>
                        <p id='PostedDate'>Posted on $announcement[announcementDate]</p>
                        <div class='button-container'>
                            <img id='EditImgButton' src='images/edit.png' alt='Edit'>
                            <a href='manager/delete_announcement.php?id=$announcement[announcementId]&eventId=$event[eventId]'><img id='DeleteImgButton' src='images/delete.png' alt='Delete'></a>
                        </div>
                    </div>
                    <div class='edit-announcement'>
                        <hr><br>
                        <form action='manager/edit_announcement.php' method='post'>
                            <input type='hidden' name='announcementId' value='".$announcement['announcementId']."'>
                            <input type='hidden' name='eventId' value='".$event['eventId']."'>
                            <p>Title:<br><br><input type='text' name='announcementTitle' id='announcementTitle' value='".$announcement['announcementName']."'></p>
                            <p>Description:<br><br><textarea name='announcementDescription' id='announcementDescription'>".str_replace(array('<br />', '<br>', '<br/>'), '', $announcement['announcementDescription'])."</textarea></p>
                            <div class='button-container'>
                                <button id='editButton'>Confirm</button>
                                <button id='cancelButton'>Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            ");
        }
        echo("
                </div>
            </div>
        ");

        echo("
            <script>
                var announcements = document.getElementsByClassName('announcement');
                for (let i= 0; i < announcements.length; i++) {
                    var editImgButton = announcements[i].getElementsByTagName('img')[0];
                    editImgButton.onclick = function(){
                        var editAnnouncement = announcements[i].getElementsByClassName('edit-announcement')[0];
                        editAnnouncement.style.display = editAnnouncement.style.display == 'none' ? 'block' : 'none';
                    }

                    var cancelButton = announcements[i].getElementsByTagName('button')[1];
                    cancelButton.onclick = function(){
                        event.preventDefault();
                        var editAnnouncement = announcements[i].getElementsByClassName('edit-announcement')[0];
                        editAnnouncement.style.display = 'none';
                    }
                }
            </script>
        ");
    }

    function displayFAQ($faqs){
        foreach ($faqs as $faq){
            echo("
                <div class='content-block'>
                    <h2 id='Question'>$faq[faqQuestion]</h2>
                    <p id='Answer'>$faq[faqAnswer]</p>
                    <div class='content-bottom'>
                        <p id='PostedBy'>$faq[faqSeverity] Severity | Posted by $faq[username] </p>
                        <div class='button-container'>
                            <button id='EditButton'>Edit</button>
                            <a href='manager/remove_faq.php?id=$faq[faqId]'><button id='RemoveButton'>Remove</button></a>
                        </div>
                    </div>
                    <div class='edit-faq'>
                        <hr><br>
                        <form action='manager/edit_faq.php' method='post'>
                            <input type='hidden' name='faqId' value='".$faq['faqId']."'>
                            <p>Question:<br><br><input type='text' name='faqQuestion' id='faqQuestion' value='".$faq['faqQuestion']."'></p>
                            <p>Answer:<br><br><textarea name='faqAnswer' id='faqAnswer'>".str_replace(array('<br />', '<br>', '<br/>'), '', $faq['faqAnswer'])."</textarea></p>
                            <p>Severity:
                                <select name='faqSeverity' id='faqSeverity'>
                                    <option value='Low'");
                                    if ($faq['faqSeverity'] == 'Low') echo(" selected");
                                    echo(" >Low</option>
                                    <option value='Medium'");
                                    if ($faq['faqSeverity'] == 'Medium') echo(" selected");
                                    echo(" >Medium</option>
                                    <option value='High'");
                                    if ($faq['faqSeverity'] == 'High') echo (" selected");
                                    echo(">High</option>
                                </select>
                            </p>
                            <div class='button-container'>
                                <button id='editButton'>Confirm</button>
                                <button id='cancelButton'>Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            ");
        }

        echo("
            <script>
                var contentBlocks = document.getElementsByClassName('content-block');
                for (let i= 0; i < contentBlocks.length; i++) {
                    var editButton = contentBlocks[i].getElementsByTagName('button')[0];
                    editButton.onclick = function(){
                        var editFAQ = contentBlocks[i].getElementsByClassName('edit-faq')[0];
                        editFAQ.style.display = editFAQ.style.display == 'none' ? 'block' : 'none';
                    }

                    var cancelButton = contentBlocks[i].getElementsByTagName('button')[3];
                    cancelButton.onclick = function(){
                        event.preventDefault();
                        var editFAQ = contentBlocks[i].getElementsByClassName('edit-faq')[0];
                        editFAQ.style.display = 'none';
                    }
                }
            </script>
        ");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/searchStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/eventsStyle.css">
    <link rel="stylesheet" href="css/managerStyle.css">
    <title>Manager</title>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon" onclick="searchEvents()"><hr>
        <script>
            var searchBar = document.getElementById('searchBar');
            searchBar.addEventListener('keyup', function(event){
                var contentBlock = document.getElementsByClassName('content-block');
                var searchValue = event.target.value.toLowerCase();

                currentPage = new URLSearchParams(window.location.search).get('page');
                    switch (currentPage) {
                        case 'newsManager':                
                            Array.from(contentBlock).forEach(function(contentBlock){
                                var newsName = contentBlock.getElementsByTagName('h2')[0].innerText;
                                var newsDescription = contentBlock.getElementsByTagName('p')[0].innerText;
                                var newsDetails = contentBlock.getElementsByTagName('p')[1].innerText;

                                if(newsName.toLowerCase().indexOf(searchValue) != -1 || 
                                    newsDescription.toLowerCase().indexOf(searchValue) != -1 || 
                                    newsDetails.toLowerCase().indexOf(searchValue) != -1){
                                    contentBlock.style.display = 'block';
                                } else {
                                    contentBlock.style.display = 'none';
                                }
                            });
                        break;
                    case 'roleManager':
                            var tableRow = document.getElementsByTagName('tr');
                            Array.from(tableRow).forEach(function(tableRow){
                                if (tableRow.getElementsByTagName('th').length > 0) return;

                                var username = tableRow.getElementsByTagName('td')[0].innerText;
                                var userId = tableRow.getElementsByTagName('td')[1].innerText;
                                var contactNum = tableRow.getElementsByTagName('td')[2].innerText;
                                var email = tableRow.getElementsByTagName('td')[3].innerText;

                                if(username.toLowerCase().indexOf(searchValue) != -1 || 
                                    userId.toLowerCase().indexOf(searchValue) != -1 || 
                                    contactNum.toLowerCase().indexOf(searchValue) != -1 || 
                                    email.toLowerCase().indexOf(searchValue) != -1){
                                    tableRow.style.display = 'table-row';
                                } else {
                                    tableRow.style.display = 'none';
                                }
                            });
                        break;
                    case 'eventManager':
                            var tableRow = document.getElementsByTagName('tr');
                            Array.from(tableRow).forEach(function(tableRow){
                                if (tableRow.getElementsByTagName('th').length > 0) return;

                                var eventName = tableRow.getElementsByTagName('td')[0].innerText;
                                var hostName = tableRow.getElementsByTagName('td')[1].innerText;
                                var venue = tableRow.getElementsByTagName('td')[2].innerText;
                                var dateAndTime = tableRow.getElementsByTagName('td')[3].innerText;
                                var fee = tableRow.getElementsByTagName('td')[4].innerText;

                                if(eventName.toLowerCase().indexOf(searchValue) != -1 || 
                                    hostName.toLowerCase().indexOf(searchValue) != -1 || 
                                    venue.toLowerCase().indexOf(searchValue) != -1 || 
                                    dateAndTime.toLowerCase().indexOf(searchValue) != -1 || 
                                    fee.toLowerCase().indexOf(searchValue) != -1){
                                    tableRow.style.display = 'table-row';
                                } else {
                                    tableRow.style.display = 'none';
                                }
                            });
                        break;
                    case 'announcementManager':
                            var announcement = document.getElementsByClassName('announcement');
                            Array.from(announcement).forEach(function(announcement){
                                var announcementName = announcement.getElementsByTagName('h3')[0].innerText;
                                var announcementDescription = announcement.getElementsByTagName('p')[0].innerText;
                                var postedDate = announcement.getElementsByTagName('p')[1].innerText;

                                if(announcementName.toLowerCase().indexOf(searchValue) != -1 || 
                                    announcementDescription.toLowerCase().indexOf(searchValue) != -1 || 
                                    postedDate.toLowerCase().indexOf(searchValue) != -1){
                                    announcement.style.display = 'block';
                                } else {
                                    announcement.style.display = 'none';
                                }
                            });
                        break;
                    case 'faqManager':
                            var contentBlock = document.getElementsByClassName('content-block');
                            Array.from(contentBlock).forEach(function(contentBlock){
                                var question = contentBlock.getElementsByTagName('h2')[0].innerText;
                                var answer = contentBlock.getElementsByTagName('p')[0].innerText;
                                var details = contentBlock.getElementsByTagName('p')[1].innerText;

                                if(question.toLowerCase().indexOf(searchValue) != -1 || 
                                    answer.toLowerCase().indexOf(searchValue) != -1 || 
                                    details.toLowerCase().indexOf(searchValue) != -1){
                                    contentBlock.style.display = 'block';
                                } else {
                                    contentBlock.style.display = 'none';
                                }
                            });
                        break;
                    default:
                        break;
                    }
            });
        </script>
    </div>
    <div class="main">
        <div class="buttons">
            <button id="NewsButton">News</button>
            <button id="RoleButton">Role</button>
            <button id="EventButton">Event</button>
            <button id="FAQButton">FAQ</button>
            <script>
                document.getElementById("NewsButton").onclick = function(){
                    window.location.href = "manager.php?page=newsManager";
                }
                document.getElementById("RoleButton").onclick = function(){
                    window.location.href = "manager.php?page=roleManager";
                }
                document.getElementById("EventButton").onclick = function(){
                    window.location.href = "manager.php?page=eventManager";
                }
                document.getElementById("FAQButton").onclick = function(){
                    window.location.href = "manager.php?page=faqManager";
                }

                var page = new URLSearchParams(window.location.search).get('page');
                switch (page) {
                    case 'newsManager':
                        var button = document.getElementById("NewsButton");
                        break;
                    case 'roleManager':
                        var button = document.getElementById("RoleButton");
                        break;
                    case 'eventManager':
                    case 'announcementManager':
                        var button = document.getElementById("EventButton");
                        break;
                    case 'faqManager':
                        var button = document.getElementById("FAQButton");
                        break;
                    default:
                        break;
                }
                if (button) {
                    button.style.backgroundColor = "#ff6a00";
                    button.style.color = "#ffffff";
                    button.style.border = "none";
                }
            </script>
        </div>
        </div>
        <div class="content">
            <?php
                if(isset($_GET['page'])){
                    if ($_GET['page'] == 'newsManager'){
                        displayNews($news);
                    }
                    else if ($_GET['page'] == 'roleManager'){
                        displayRole($roles);
                    }
                    else if ($_GET['page'] == 'eventManager'){
                        displayEvent($events);
                    }
                    else if ($_GET['page'] == 'faqManager'){
                        displayFAQ($faqs);
                    }
                    else if ($_GET['page'] == 'announcementManager'){
                        displayAnnouncement($event, $announcements);
                    }
                }
            ?>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>