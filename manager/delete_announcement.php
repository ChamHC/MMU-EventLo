<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $id = $_GET['id'];
    $eventID = $_GET['eventId'];
    $sql = "DELETE FROM announcement WHERE announcementID = $id";
    $result = $conn->query($sql);

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=announcementManager&id=$eventID");
    }else{
        echo "<script>alert('Delete failed');</script>";
    }
?>