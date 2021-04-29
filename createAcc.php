<?php
    require_once "dataBaseConnector.php";

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0){
        echo "Error: ".$connection -> connect_errno. "Description: ".$connection -> connect_errno;
        
    } else {
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $phone_number = $_POST['phone_number'];

        $connection -> close();
    }

?>