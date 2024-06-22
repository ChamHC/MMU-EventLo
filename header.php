<?php
    require_once 'trackRole.php';
    $userRole = checkUserRole();

    echo(
        '
        <header>
        <a href="index.html"><img src="images/logo.png" alt="MMU Event Organizer" class="logo"></a>
        <nav class="main-nav">
            <ul>
                <li><a href="index.php" id="Home">Home</a></li>
                <li><a href="catalogue.php" id="ECatalogue">E-Catalogue</a></li>
                <li><a href="events.php" id="Events">My Events</a></li>
                <li><a href="faq.php" id="FAQs">FAQs</a></li>
                <li><a href="aboutUs.php" id="AboutUs">About Us</a></li>
            </ul>
        </nav>
        <img src="images/profile.png" alt="Profile" class="profile-icon" onclick="">
        <nav class="profile-nav">
            <ul>
                <li><a href="profile.php">Profile</a></li>
    ');
    if ($userRole == 'Admin') echo '<li><a href="manager.php">Manager</a></li>';
    echo('
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </nav>
        </header>
        '
    );

    $includedFile = basename($_SERVER['PHP_SELF']);
    switch ($includedFile) {
        case 'catalogue.php':
        case 'catalogueDetails.php':
            setColor('ECatalogue');
            break;
        case 'events.php':
        case 'announcement.php':
            setColor('Events');
            break;
        case 'faq.php':
        case 'faqForm.php':
            setColor('FAQs');
            break;
        case 'aboutUs.php':
            setColor('AboutUs');
            break;
        default:
            setColor('Home');
            break;
    }

    function setColor($id){
        echo ("
            <script>
                var link = document.getElementById('$id');
                link.style.color = 'white';
            </script>
        ");
    }
?>