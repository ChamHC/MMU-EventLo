<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $faqID = $_GET['id'];
    echo $faqID;

    $query = "DELETE FROM faq WHERE faqID = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("i", $faqID);

    $result = $statement->execute();

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=faqManager");
    }else{
        echo "<script>alert('Delete failed');</script>";
    }
?>