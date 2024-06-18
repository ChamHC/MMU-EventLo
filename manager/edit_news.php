<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $newsID = $_POST['newsId'];
    $newsTitle = $_POST['newsTitle'];
    $newsDescription = $_POST['newsDescription'];
    $newsDate = date('Y-m-d');

    $query = "UPDATE news SET newsName = '$newsTitle', newsDescription = '$newsDescription', newsDate = '$newsDate' WHERE newsID = $newsID";
    $result = $conn->query($query);

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=newsManager");
    }else{
        echo "<script>alert('Update failed');</script>";
    }
?>