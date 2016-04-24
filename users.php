<?php
    session_start();
    //Connection.
	$dbh = new PDO('mysql:host=localhost;dbname=ajax', 'root', '');

    //Get values from ajax.
    $username = $_POST['username'];
    $password = $_POST['password'];
    $action = $_GET['action'];

    if($action == "register"){
        //Check for existing user first.
        $sql = $dbh->prepare("SELECT * FROM user WHERE username = '".$username."'");
        $sql->execute();
        $count = $sql->rowCount();

        if($count > 0){
            //Name taken.
            echo "<h3>Username already taken; try a new one.</h3>";
        }else {
            //Prepare the insert.
            $sql = $dbh->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
            $sql->bindParam(':username', $username);
            $sql->bindParam(':password', $password);

            //Execute.
            $sql->execute();

            //Confirmation message.
            echo "<h3>Thank you for registering with us, " . $username . "!</h3>";
        }
    }elseif($action == "login"){
        $sql = $dbh->prepare("SELECT * FROM user WHERE username = '".$username."' AND password = '".$password."'");

        $sql->execute();

        $row = $sql->fetch();

        $count = $sql->rowCount();

        if($count > 0){
            echo "<h3>You are now logged in.</h3>";
            $_SESSION['username'] = $username;

            //Check for admin status
            if($row['admin'] == '1'){
                $_SESSION['admin'] = true;
            }
        }else{
            echo "<h3>Wrong information; try again.</h3>";
        }
    }elseif($action == "logout"){
        //The reason I'm not echoing you are logged out right here is because I'm not using a post call for this function.
        //I'm using an ajax call, which requires a success callback function; that's where I'm displaying that message.
        session_destroy();
    }
?>