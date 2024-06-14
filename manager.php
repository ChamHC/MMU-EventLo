<?php
    if (!isset($_GET['page'])) header('Location: manager.php?page=newsManager');

    require 'db_connect.php';
    $conn = OpenCon();
    $news_sql = "SELECT * FROM news";
    $news_result = $conn->query($news_sql);

    $news = array();

    if ($news_result->num_rows > 0) {
        while ($row = $news_result->fetch_assoc()) {
            $newsId = $row['newsID'];
            $newsName = $row['newsName'];
            $newsDate = $row['newsDate'];
            $newsDescription = $row['newsDescription'];
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
                            <img id='DeleteImgButton' src='images/delete.png' alt='Delete'>
                        </div>
                    </div>
                    </div>
                </div>
                <img id='Add' src='images/add.png' alt='Add'>
            ");
        }
    }

    function displayRole(){
        echo ("
            <div class='content-block'>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    <tr>
                        <td>Cham Hao Cheng</td>
                        <td>1211304951</td>
                        <td>0123456789</td>
                        <td>1211304951@student.mmu.edu.my</td>
                        <td>
                            <button id='ApproveButton'>Approve</button>
                            <button id='RejectButton'>Reject</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Cham Hao Cheng</td>
                        <td>1211304951</td>
                        <td>0123456789</td>
                        <td>1211304951@student.mmu.edu.my</td>
                        <td>
                            <button id='ApproveButton'>Approve</button>
                            <button id='RejectButton'>Reject</button>
                        </td>
                    </tr>
                </table>
            </div>
        ");
    }

    function displayEvent(){
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
                    </tr>
                    <tr>
                        <td>MMU Book Exchange</td>
                        <td>MMU Book Club</td>
                        <td>MMU Library</td>
                        <td>26th April 2024</td>
                        <td>Free</td>
                        <td>
                            <button id='ManageButton'>Manage</button>
                            <button id='RemoveButton'>Remove</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Larian Studio Visit</td>
                        <td>MMU Game Development Club</td>
                        <td>Larian Studio</td>
                        <td>1st May 2024, 10.00am - 4.00pm</td>
                        <td>RM20</td>
                        <td>
                            <button id='ManageButton'>Manage</button>
                            <button id='RemoveButton'>Remove</button>
                        </td>
                    </tr>
                </table>
            </div>
            <script>
                document.getElementById('ManageButton').onclick = function(){
                    window.location.href = 'manager.php?page=announcementManager';
                }
            </script>
        ");
    }

    function displayAnnouncement(){
        echo("
            <div class='content-block'>
                <div class='event-container' style='border: none'>
                    <img src='images/logo.png' alt='Event Cover Image'>
                    <div class='event-details'>
                        <h2>心战 Suffocate</h2><hr>
                        <p id='hostedBy'>Hosted by <span id='host'> Chinese Language Society Multimedia Unversity(Cyberjaya)Stage Show</span></p>
                        <p>Date and Time: <span id='dateAndTime'>14th June 2024 8pm-10pm</span></p>
                        <p>Venue: <span id='venue'>Dewan Tun Canselor</span></p>
                        <p>Fee: <span id='fee'>Free</span></p>
                        <p>Capacity: <span id='capacity'>500</span></p>
                        <p>Description: <br><span id='description'>
                        复杂的人物关系，让人摸不着的头绪，猜不透的下一幕，生活中的酸甜苦辣，处处围绕在你我身边的社会问题 ，
                        充满惊喜的铺垫和意想不到的结局。人的内心总会被一种名为“情感”的力量操控🫴🏽，在心与心之间的战斗中，
                        人需要谨慎地在十字路口做出关键选择🔑，从而确保自己踏上那条正确的道路🛤️.<br><br>
                        主角究竟经历了什么事情？真相又究竟是如何？这就得靠你们来亲自感受我们的舞台剧啦！<br><br>
                        Complex interpersonal relationships, perplexing plots that keep everyone guessing about the next scene, 
                        the ups and downs of life, and societal issues surrounding us - all filled with surprises leading to 
                        unexpected endings.The human inner self is often influenced by a force called “emotion.”🫴🏽 
                        In the inner conflict of the heart and mind, others need to carefully make the final choices 🔑 
                        at crossroads, determining the direction they take forward🛤️.<br><br> 
                        What has the protagonist experienced? What is the truth behind it all? You’ll have to experience our 
                        Stage Show firsthand to find out!
                        </span></p>
                    </div>
                </div>
                <div class='announcements-container'>
                    <h2>Announcements</h2>
                    <div class='announcement'>
                        <p id='Details'>The event will be held in Dewan Tun Canselor, MMU Cyberjaya. Please be punctual.</p>
                        <div class='content-bottom'>
                            <p id='PostedDate'>Posted on 10th June 2024</p>
                            <div class='button-container'>
                                <img id='EditImgButton' src='images/edit.png' alt='Edit'>
                                <img id='DeleteImgButton' src='images/delete.png' alt='Delete'>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ");
    }

    function displayFAQ(){
        echo("
            <div class='content-block'>
                <h2 id='Question'>What is MMU EventLo?</h2>
                <p id='Answer'>MMU EventLo is a platform designed for MMU students to discover, participate in, and create events.</p>
                <div class='content-bottom'>
                    <p id='PostedBy'>Posted by Cham Hao Cheng</p>
                    <div class='button-container'>
                        <button id='EditButton'>Edit</button>
                        <button id='RemoveButton'>Remove</button>
                    </div>
                </div>
            </div>
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

                var page = window.location.href.split('=')[1];
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
                    switch ($_GET['page']) {
                        case 'newsManager':
                            displayNews($news);
                            break;
                        case 'roleManager':
                            displayRole();
                            break;
                        case 'eventManager':
                            displayEvent();
                            break;
                        case 'announcementManager':
                            displayAnnouncement();
                            break;
                        case 'faqManager':
                            displayFAQ();
                            displayFAQ();
                            displayFAQ();
                            break;
                        default:
                            echo 
                            "<script>
                                alert('Invalid Page.');
                                window.location.href = 'manager.php?page=newsManager';
                            </script>";
                            break;
                    }
                }
            ?>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>