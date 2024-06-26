<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $newsID = $_POST['newsId'];
    $newsTitle = $_POST['newsTitle'];
    $newsDescription = $_POST['newsDescription'];
    $newsDate = date('Y-m-d');

    $query = "UPDATE news SET newsName = ?, newsDescription = ?, newsDate = ? WHERE newsID = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("sssi", $newsTitle, $newsDescription, $newsDate, $newsID);
    $result = $statement->execute();

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=newsManager");
    }else{
        echo "<script>alert('Update failed');</script>";
    }
?>