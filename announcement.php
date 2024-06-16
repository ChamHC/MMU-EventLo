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
    <title>SelectedCatalogueDetails</title>
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
                <p><span id="description">
                    announcementDetails.announcementDetails.hdjkashjashdkahsjdhkahsdhajskdjashdkahskdasdadasdasdasdasdasdashdjkashjashdkahsjdhkahsdhajskdjashdkahskdasdadasdasdasdasdasdas
                </span></p>
            </div>
            <div class='content-bottom'>
                <div class='button-container'>
                    <img id='EditImgButton' src='images/edit.png' alt='Edit'>
                    <img id='DeleteImgButton' src='images/delete.png' alt='Delete'>
                </div>
                <p id="datePost">Date: <span id="date">10th May 2024</span></p>
            </div>
        </div>
        <div class="add-button-container">
            <img src="images/add.png" alt="Add" id="Add" onclick="openAnnouncementPanel()">
        </div>  
        <div class="announcement-panel" id="uploadAnnouncementPanel">
            <div class="close-button" onclick="closeAnnouncementPanel()">&times;</div>
            <div class="announcement-panel-inner">
                <form id="announcementUploadForm">
                    <label for="announcementTitle">Title:</label>
                    <textarea id="announcementTitle" name="announcementTitle" required></textarea>
                    <br>
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                    <br>
                    <input type="file" id="announcement" name="announcement" accept="image/*" required>
                    <div class="button-container">
                        <button type="button" onclick="uploadAnnouncement()">Upload</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="announcementOutput" style="display: none; margin-top: 20px; padding: 20px; border: 2px solid black; border-radius: 5px;">
            <h3>Announcement Preview</h3>
            <p><strong>Title:</strong> <span id="outputTitle"></span></p>
            <p><strong>Description:</strong> <span id="outputDescription"></span></p>
            <p><strong>Image:</strong> <br> <img id="outputImage" src="" alt="Announcement Image" style="max-width: 100%; height: auto;"></p>
        </div>
    </div>
    <script>
    // Open the pop-up panel
    function openAnnouncementPanel() {
        console.log("Opening announcement panel");
        document.getElementById("uploadAnnouncementPanel").style.display = "block";
    }

    // Close the pop-up panel
    function closeAnnouncementPanel() {
        console.log("Closing announcement panel");
        document.getElementById("uploadAnnouncementPanel").style.display = "none";
    }

    // Display the user's input
    function uploadAnnouncement() {
        const title = document.getElementById("announcementTitle").value;
        const description = document.getElementById("description").value;
        const fileInput = document.getElementById("announcement");
        const file = fileInput.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("outputImage").src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById("outputImage").src = "";
        }

        document.getElementById("outputTitle").innerText = title;
        document.getElementById("outputDescription").innerText = description;
        document.getElementById("announcementOutput").style.display = "block";
        
        // Optionally, close the announcement panel
        closeAnnouncementPanel();
    }
    </script>
</body>
</html>
