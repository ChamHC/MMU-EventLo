<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $faqID = $_POST['faqId'];
    $faqQuestion = $_POST['faqQuestion'];
    $faqAnswer = $_POST['faqAnswer'];
    $faqSeverity = $_POST['faqSeverity'];

    $query = "UPDATE faq SET question = ?, answer = ?, severityQuestion = ? WHERE faqID = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("sssi", $faqQuestion, $faqAnswer, $faqSeverity, $faqID);

    $result = $statement->execute();

    CloseCon($conn);

    if($result){
        header("Location: ../manager.php?page=faqManager");
    }else{
        echo "<script>alert('Update failed');</script>";
    }
?>