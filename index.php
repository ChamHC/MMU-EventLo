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

// Query to get all news
$query_news = "
    SELECT newsID, newsName, newsDate, newsDescription
    FROM news
    ORDER BY newsDate DESC
";
$result_news = mysqli_query($conn, $query_news);

$news_items = array();

while ($row = mysqli_fetch_assoc($result_news)) {
    $news_items[] = array(
        'newsID' => $row['newsID'],
        'newsName' => $row['newsName'],
        'newsDate' => date('j F Y', strtotime($row['newsDate'])),
        'newsDescription' => $row['newsDescription']
    );
}

// Query to get the upcoming 3 events by eventDate
$query_upcoming = "
    SELECT event.eventID, event.eventName, event.eventLocation, event.eventDate, event.eventPicture, user.username 
    FROM event 
    JOIN user ON event.userID = user.userID
    WHERE event.eventDate >= CURDATE()
    ORDER BY event.eventDate ASC
    LIMIT 3
";
$result_upcoming = mysqli_query($conn, $query_upcoming);

$upcoming_events = array();

while ($row = mysqli_fetch_assoc($result_upcoming)) {
    $upcoming_events[] = array(
        'eventID' => $row['eventID'],
        'eventName' => $row['eventName'],
        'eventLocation' => $row['eventLocation'],
        'eventDate' => date('j F Y', strtotime($row['eventDate'])),
        'eventPicture' => 'data:image/jpeg;base64,' . base64_encode($row['eventPicture']),
        'username' => $row['username']
    );
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
    <link rel="stylesheet" href="css/index.css">
    <title>Events overview</title>
</head>

<body>

    <?php include 'header.php' ?>

    <h1>Events overview</h1>
    <hr class="divider">
    <h2>News</h2>
    <!-- Slideshow container -->
    <div class="slideshow-container">
        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <?php foreach ($news_items as $index => $news): ?>
            <div class="mySlides">
                <div class="slide-content">
                    <div class="text">
                        <p class="news-name"> <?= $news['newsName']; ?></p>
                        <p>Date: <?= $news['newsDate']; ?></p>
                        <p>Description: <?= $news['newsDescription']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>
    <br>

    <!-- The dots/circles -->
    <div style="text-align:center">
        <?php foreach ($news_items as $index => $news): ?>
            <span class="dot" onclick="currentSlide(<?= $index + 1; ?>)"></span>
        <?php endforeach; ?>
    </div>

    <h2>Recent Events</h2>
    <div class="recent-events">
        <?php foreach ($upcoming_events as $event): ?>
            <div class="recent-event">
                <img src="<?= $event['eventPicture']; ?>" class="recent-event-img" onclick="navigateToCatalogueDetailsPage(<?= $event['eventID']; ?>, <?= $userId; ?>)">
                <p><?= $event['eventName']; ?></p>
                <p>Date: <?= $event['eventDate']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <footer><?php include 'footer.php' ?></footer>

    <script>
        let slideIndex = 1;
        let timer;

        function plusSlides(n) {
            clearTimeout(timer);
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            clearTimeout(timer);
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "flex";
            dots[slideIndex - 1].className += " active";
            timer = setTimeout(() => plusSlides(1), 3000); // Change slide every 3 seconds
        }

        showSlides(slideIndex);

        function navigateToCatalogueDetailsPage(eventId, userId) {
            window.location.href = "catalogueDetails.php?eventId=" + eventId + "&userId=" + userId;
        }
    </script>
</body>

</html>
