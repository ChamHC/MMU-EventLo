<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $announcementID = $_POST['announcementId'];
    $announcementName = $_POST['announcementTitle'];
    $announcementDescription = $_POST['announcementDescription'];
    $announcementDate = date('Y-m-d');
    $eventID = $_POST['eventId'];
    $userID = 1;    
    
    $query = "UPDATE announcement SET announcementName = ?, announcementDescription = ?, announcementDate = ?, eventID = ?, userID = ? WHERE announcementID = ?";
    $statement = $conn->prepare($query);
    $statement -> bind_param("sssiii", $announcementName, $announcementDescription, $announcementDate, $eventID, $userID, $announcementID);
    $result = $statement->execute();

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=announcementManager&id=$eventID");
    }else{
        echo "<script>alert('Update failed');</script>";
    }
?>