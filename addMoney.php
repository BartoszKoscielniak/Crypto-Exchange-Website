<?php
    require_once "dataBaseConnector.php";
    session_start();

    $connection = @new mysqli($host, $db_user, $db_password, $db_name);


    $fileName = $_POST['area'];
    $moneyAmount = $_POST['amountttt'];
    
    $aa = intval($moneyAmount) + 5;

    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_error());
    } else {
    $result = $connection->query("SELECT p.ilość_euro FROM portfele p  WHERE p.id_użytkownika = '" . $_SESSION['id_użytkownika'] . "'");
    $_SESSION['euro'] = $result->fetch_all();
    $result->free();

    $amoutAfterTransaction =  round($_SESSION['euro'][0][0] + $moneyAmount, 3) ;

    $connection->query("UPDATE portfele SET ilość_euro='".$amoutAfterTransaction."' WHERE id_użytkownika = '".$_SESSION['id_użytkownika']."' ");

    $connection->close();

    header('Location:'.$fileName);


    }


?>