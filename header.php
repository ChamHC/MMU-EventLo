<?php
    echo(
        '
        <header>
        <a href="index.html"><img src="images/logo.png" alt="MMU Event Organizer" class="logo"></a>
        <nav class="main-nav">
            <ul>
                <li><a href="index.html" id="Home">Home</a></li>
                <li><a href="catalogue.html" id="ECatalogue">E-Catalogue</a></li>
                <li><a href="events.php" id="Events">Events</a></li>
                <li><a href="faqs.html" id="FAQs">FAQs</a></li>
                <li><a href="aboutUs.html" id="AboutUs">About Us</a></li>
            </ul>
        </nav>
        <img src="images/profile.png" alt="Profile" class="profile-icon" onclick="">
        <nav class="profile-nav">
            <ul>
                <li><a href="profile.html">Profile</a></li>
                <li><a href="login.html">Log Out</a></li>
            </ul>
        </nav>
        </header>
        '
    );

    $includedFile = basename($_SERVER['PHP_SELF']);
    switch ($includedFile) {
        case 'events.php':
            setColor('Events');
            break;
        case 'manager.php':
            setColor('Home');
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