<?php
    require '../db_connect.php';
    $conn = OpenCon();

    $userId = $_GET['id'];
    if ($_GET['action'] == "approve"){
        $query = "UPDATE user SET role = 'Host' WHERE userID = $userId";
    }
    else{
        $query = "UPDATE user SET applyHost = 0 WHERE userID = $userId";
    }

    $result = $conn->query($query);

    CloseCon($conn);

    if ($result) {
        header("Location: ../manager.php?page=roleManager");
    } else {
        echo "<script>alert('Action failed');</script>";
    }
?>