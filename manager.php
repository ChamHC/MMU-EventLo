<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/resetStyle.css">
    <link rel="stylesheet" href="css/headerStyle.css">
    <link rel="stylesheet" href="css/footerStyle.css">
    <link rel="stylesheet" href="css/searchStyle.css">
    <link rel="stylesheet" href="css/managerStyle.css">
    <title>Manager</title>
    <style>
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }
        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }
        .buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            width: 99%;
        }
        .buttons button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #ff914d;
            cursor: pointer;
            font-weight: bold;
            width: 25%;
            border: solid 2px black;
        }
        .buttons button:hover {
            background-color: #ff6a00;
            color: white;
        }
        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .content-block {
            width: 98%;
            padding: 20px;
            border: 1px solid #000000;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content-main {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 0 20px;
        }
        .content-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        #EditButton, #DeleteButton {
            width: 50px;
            height: 50px;
            cursor: pointer;
        }
        #Add {
            width: 50px;
            height: 50px;
            margin: 0 0 20px 0;
            cursor: pointer;
        }
        #Description {
            margin: 20px 0;
        }
        #ContentDetails {
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php' ?>
    <div class="search">
        <input type="text" id="searchBar" placeholder="Search...">
        <img src="images/searchIcon.png" alt="Search" id="searchIcon" onclick="searchEvents()"><hr>
    </div>
    <div class="main">
        <div class="buttons">
            <button id="NewsButton" onclick="">News</button>
            <button id="RoleButton" onclick="">Role</button>
            <button id="EventButton" onclick="">Event</button>
            <button id="FAQButton" onclick="">FAQ</button>
        </div>
        <div class="content">
            <div class="content-block">
                <h2>eBwise is replacing MMLS</h2><hr>
                <div class="content-main">
                <p id="Description">
                    Dear MMU Students, <br><br>
                    We are excited to announce that MMU is transitioning from the existing Learning Management System (LMS), MMLS to a new
                    Moodle-based LMS called eBwise. This transition marks a significant upgrade in our technological infrastructure to enhance your learning experience.
                </p> 
                <div class="content-bottom">
                    <p id="ContentDetails">
                        Date: 15th March 2024<br>
                        Posted by PROF. DR WONG HIN YONG
                    </p>
                    <div class="button-container">
                        <img id="EditButton" src = "images/edit.png" alt="Edit">
                        <img id="DeleteButton" src = "images/delete.png" alt="Delete">
                    </div>
                </div>
                </div>
            </div>
            <div class="content-block">
                <h2>eBwise is replacing MMLS</h2><hr>
                <div class="content-main">
                <p id="Description">
                    Dear MMU Students, <br><br>
                    We are excited to announce that MMU is transitioning from the existing Learning Management System (LMS), MMLS to a new
                    Moodle-based LMS called eBwise. This transition marks a significant upgrade in our technological infrastructure to enhance your learning experience.
                </p> 
                <div class="content-bottom">
                    <p id="ContentDetails">
                        Date: 15th March 2024<br>
                        Posted by PROF. DR WONG HIN YONG
                    </p>
                    <div class="button-container">
                        <img id="EditButton" src = "images/edit.png" alt="Edit">
                        <img id="DeleteButton" src = "images/delete.png" alt="Delete">
                    </div>
                </div>
                </div>
            </div>
            <img id="Add" src="images/add.png" alt="Add">
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>
</html>