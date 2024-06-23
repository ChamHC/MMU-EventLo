<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $time24Hour = $_POST['eventsTime'];
    $dateTime = DateTime::createFromFormat('H:i', $time24Hour);
    $time12Hour = $dateTime->format('h:i A');

    if(!empty($_FILES["eventsImage"]["name"])){
        $fileName = basename($_FILES["eventsImage"]["name"]);
        $fileSize = $_FILES["eventsImage"]["size"];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileType = strtolower($fileExt);

        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if(in_array($fileType, $allowTypes)){
            if($fileSize <= 1048576){ 
                $image = $_FILES['eventsImage']['tmp_name'];
                $imgContent = file_get_contents($image);
            } else {
                echo "<script>alert('File size exceeds 1MB. Please upload a smaller file.');</script>";
                echo "<script>window.location.href='../events.php';</script>";
                exit;
            }
        }
        else{
            echo "<script>alert('Sorry, only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
            echo "<script>window.location.href='../events.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Please select an image file to upload.');</script>";
        echo "<script>window.location.href='../events.php';</script>";
        exit;
    }

    $eventsTitle = $_POST['eventsTitle'];
    $eventsDescription = $_POST['eventsDescription'];
    $eventsDate = $_POST['eventsDate'];
    $eventsTime =  $time12Hour;
    $eventsVenue = $_POST['eventsVenue'];
    $eventsFee = $_POST['eventsFee'];
    $eventsCapacity = $_POST['eventsCapacity'];
    $eventsImage = $imgContent;
    $userId = $_POST['userId'];

    $query = "INSERT INTO event (eventName, eventDescription, eventDate, eventTime, eventLocation, eventFee, eventCapacity, eventPicture, userID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $conn->prepare($query);
    $statement -> bind_param("sssssssbs", $eventsTitle, $eventsDescription, $eventsDate, $eventsTime, $eventsVenue, $eventsFee, $eventsCapacity, $eventsImage, $userId);
    $statement -> send_long_data(7, $eventsImage);
    $result = $statement->execute();

    $query2 = "SELECT eventID FROM event WHERE eventName = '$eventsTitle' AND eventDescription = '$eventsDescription' AND eventDate = '$eventsDate' AND eventTime = '$eventsTime' AND eventLocation = '$eventsVenue' AND eventFee = '$eventsFee' AND eventCapacity = '$eventsCapacity' AND userID = $userId";
    $result2 = $conn->query($query2);
    $row = $result2->fetch_assoc();
    $eventId = $row['eventID'];

    $query3 = "INSERT INTO eventuser (eventID, userID) VALUES ($eventId, $userId)";
    $result3 = $conn->query($query3);

    CloseCon($conn);

    if ($result && $result2 && $result3) {
        header("Location: ../events.php");
    } else {
        echo "<script>alert('Add failed');</script>";
    }
?>