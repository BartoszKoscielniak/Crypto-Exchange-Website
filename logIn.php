<?php
    require_once "dataBaseConnector.php";
    session_start();

    if( ((!isset($_POST['email'])) || (!isset($_POST['password']))) && ((!isset($_SESSION['emailToLogIn'])) || (!isset($_SESSION['passwordToLogIn']))) ){
        header('Location:homePage.php');
        exit();
    }

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if($connection -> connect_errno != 0){
        echo "Error: ".$connection -> connect_errno;
    } else {
        
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(isset($_SESSION['emailToLogIn']) && isset($_SESSION['passwordToLogIn'])) {$email = $_SESSION['emailToLogIn']; $password = $_SESSION['passwordToLogIn'];}

        $email = htmlentities($email,ENT_QUOTES, "UTF-8");

        if($result = @$connection -> query(
            sprintf("SELECT * FROM użytkownicy WHERE BINARY adres_email = '%s'",
            mysqli_real_escape_string($connection,$email))))
        {
            
            $correct_users = $result -> num_rows;
            
            if($correct_users == 1){
                $data = $result -> fetch_assoc();
        
                if(password_verify($password, $data['haslo'])) {

                    $_SESSION['isLoggedIn'] = true;

                    $_SESSION['id_użytkownika'] = $data['id_użytkownika'];
                    $_SESSION['imię'] = $data['imię'];
                    $_SESSION['nazwisko'] = $data['nazwisko'];
                    $_SESSION['nr_telefonu'] = $data['nr_telefonu'];
                    $_SESSION['id_portfela'] = $data['id_portfela'];
                    $_SESSION['adres_email'] = $data['adres_email'];

                    unset($_SESSION['error']);
                    $result->free();

                    header('Location:home.php');
                }else {
                    echo "Bledne dane do logowania!";

                    $_SESSION['error'] = '<span style = "color:#ff0000">Błędny login lub hasło!</span>';
                    header('Location: homePage.php#log-popup');
                }

            }else {
                echo "Bledne dane do logowania!";

                $_SESSION['error'] = '<span style = "color:#ff0000">Błędny login lub hasło!</span>';
                header('Location: homePage.php#log-popup');
            }
        }


    }


?>
