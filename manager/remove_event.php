<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $eventId = $_GET['id'];

    $query = "DELETE FROM event WHERE eventID = $eventId";
    $result = $conn->query($query);

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=eventManager");
    }else{
        echo "<script>alert('Delete failed');</script>";
    }
?>