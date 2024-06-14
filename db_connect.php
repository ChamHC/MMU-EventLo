<?php
    function OpenCon(){
        $dbHost = "localhost";
        $db = "mmu_event";
        $dbUser = "root";
        $dbPass = "";

        $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $db);

        if (!$conn){
            die("Connection failed: ".mysqli_connect_error());
        }

        return $conn;
    }

    function CloseCon($conn){
        $conn -> close();
    }
?>