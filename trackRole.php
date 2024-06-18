<?php
session_start();

require 'db_connect.php';

// Function to get role based on userID
function getUserRole($userID) {
    $conn = OpenCon();

    // Query to get role based on userID
    $sql = "SELECT role FROM user WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();

    // Close statement and connection
    $stmt->close();
    CloseCon($conn);

    return $role;
}

// Function to check if user is logged in and retrieve their role
function checkUserRole() {
    if (isset($_SESSION['mySession'])) {
        $userID = $_SESSION['mySession'];
        return getUserRole($userID);
    }
    return null; // If session or user role not found
}
?>
