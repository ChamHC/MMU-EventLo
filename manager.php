<?php
    function displayNews(){
        echo ("
            <div class='content-block'>
                <h2>eBwise is replacing MMLS</h2><hr>
                <div class='content-main'>
                <p id='Description'>
                    Dear MMU Students, <br><br>
                    We are excited to announce that MMU is transitioning from the existing Learning Management System (LMS), MMLS to a new
                    Moodle-based LMS called eBwise. This transition marks a significant upgrade in our technological infrastructure to enhance your learning experience.
                </p> 
                <div class='content-bottom'>
                    <p id='ContentDetails'>
                        Date: 15th March 2024<br>
                        Posted by PROF. DR WONG HIN YONG
                    </p>
                    <div class='button-container'>
                        <img id='EditButton' src='images/edit.png' alt='Edit'>
                        <img id='DeleteButton' src='images/delete.png' alt='Delete'>
                    </div>
                </div>
                </div>
            </div>
        ");
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
            </script>
        </div>
        </div>
        <div class="content">
            <?php
                if(isset($_GET['page'])){
                    switch ($_GET['page']) {
                        case 'newsManager':
                            displayNews();
                            break;
                        case 'roleManager':
                            displayRole();
                            break;
                        case 'eventManager':
                            echo 'eventManager';
                            break;
                        case 'faqManager':
                            echo 'faqManager';
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
            <img id="Add" src="images/add.png" alt="Add">
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>