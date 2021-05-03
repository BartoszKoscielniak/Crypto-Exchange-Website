<?php
    require_once "dataBaseConnector.php";
    session_start();

    if(!isset($_POST['amount']) || !isset($_POST['buy']) || !isset($_POST['pay'])){
        echo "xd";
    }

    echo $_POST['amount']."\n";
    echo $_POST['buy']."\n";
    echo $_POST['pay']."\n";



?>
