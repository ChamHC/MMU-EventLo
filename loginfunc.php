<?php
    session_start();

    include("db_connect.php");
    $conn=OpenCon();

    if(isset($_POST['login'])){
        // To avoid hacker
        $username = mysqli_real_escape_string($conn , $_POST['username']);
        $password = mysqli_real_escape_string($conn , $_POST['password']);
    
        $sql ="SELECT * FROM user WHERE username ='$username' AND password='$password'";
        $result= mysqli_query($conn,$sql);

        if (!$result) {
            die("Query Failed: " . mysqli_error($conn)); // This will display the error message
        }
    
        $rowcount = mysqli_num_rows($result);

        if($rowcount==1){
            $row=mysqli_fetch_array($result);
            $_SESSION["mySession"]=$row['userID'];

            // redirect to home page, now is roleTest for testing the role checking from acc
            header('Location: index.php');
            exit();
        }
        else{
            // redirect to login page
            echo "<script>alert('Incorrect username / password');
                window.location.href='login.php'</script>";
            }
    }

    if (isset($_POST['register'])) {
        header('Location: register.php');
        exit();
    }
?>