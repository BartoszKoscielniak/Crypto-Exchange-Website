<?php
    require_once "dataBaseConnector.php";
    session_start();

    $ch = curl_init();
    $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=eur&order=market_cap_desc&per_page=100&page=1&sparkline=false";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    mysqli_report(MYSQLI_REPORT_STRICT);
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    //wpisanie krytpto do bazy/aktualizacja ceny
    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($response, true);
    }
    curl_close($ch);

    $notFound = true;
    if ($_POST['sell'] >= 1000){
        $_POST['sell'] = $_POST['sell']/1000;
        for ($xdx = 0; $xdx < sizeof($decoded); $xdx++){
            if ($_POST['sell'] == $decoded[$xdx]['market_cap_rank']){
                for ($xdxx = 0; $xdxx < sizeof($_SESSION['krypto']); $xdxx++) {
                    if ($decoded[$xdx]['name'] == $_SESSION['krypto'][$xdxx][1]){
                        $_POST['sell'] = $_SESSION['krypto'][$xdxx][0];
                        break 2;
                    }
                }
            }
        }
    }

    for ($t = 0; $t < sizeof($_SESSION['lista_walut']); $t++){
        if ($_POST['sell'] == $_SESSION['lista_walut'][$t][2]){
            echo $_SESSION['lista_walut'][$t][3];
            $notFound = false;
            break;
        }
    }

    if ($notFound){
        echo "0";
    }
?>
