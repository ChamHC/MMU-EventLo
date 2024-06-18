<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $announcementID = $_POST['announcementId'];
    $announcementName = $_POST['announcementTitle'];
    $announcementDescription = $_POST['announcementDescription'];
    $announcementDate = date('Y-m-d');
    $eventID = $_POST['eventId'];
    $userID = 1;

    $query = "UPDATE announcement SET announcementName = '$announcementName', announcementDescription = '$announcementDescription', announcementDate = '$announcementDate', eventID = $eventID, userID = $userID WHERE announcementID = $announcementID";
    $result = $conn->query($query);

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=announcementManager&id=$eventID");
    }else{
        echo "<script>alert('Update failed');</script>";
    }
?>