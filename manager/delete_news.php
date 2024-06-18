<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $id = $_GET['id'];
    $sql = "DELETE FROM news WHERE newsID = $id";
    $result = $conn->query($sql);

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=newsManager");
    }else{
        echo "<script>alert('Delete failed');</script>";
    }
?>