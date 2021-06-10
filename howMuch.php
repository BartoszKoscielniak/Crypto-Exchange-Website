<?php
    require_once "dataBaseConnector.php";
    session_start();


    if (is_numeric($_POST['buy'])){

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

        for ($xdx = 0; $xdx < sizeof($decoded); $xdx++){
            if ($_POST['buy'] == $decoded[$xdx]['market_cap_rank']){
                $_POST['buy'] = $decoded[$xdx]['name'];
                break;
            }
        }
    }

    if ($_POST['pay'] == 'myWallet'){
        for($i = 0; $i < sizeof($_SESSION['krypto']); $i++){
            if ($_POST['buy'] == $_SESSION['krypto'][$i][1]){
                $canBuy = $_SESSION['portfel'][0][2] / $_SESSION['krypto'][$i][2];
                $canBuy = $canBuy * 0.9998;
                echo number_format((float)$canBuy, 5, '.', '');
            }
        }
    }else {
        for ($i = 0; $i < sizeof($_SESSION['krypto']); $i++) {
            if ($_POST['pay'] == $_SESSION['krypto'][$i][1]) {//znajdujemy krypto ktorym placimy
                for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                    if ($_POST['buy'] == $_SESSION['krypto'][$a][1]) {//znajdujemy krypto ktore kupujemy
                        $exRatio = $_SESSION['krypto'][$i][2] / $_SESSION['krypto'][$a][2];
                        $idPayCrypto = $_SESSION['krypto'][$i][0];
                        for ($k = 0; $k < sizeof($_SESSION['lista_walut']); $k++){
                            if ($idPayCrypto == $_SESSION['lista_walut'][$k][2]){
                                $canBuy = ($_SESSION['lista_walut'][$k][3] * $exRatio)*0.9998;
                                //echo $_SESSION['lista_walut'][$k][3];
                                echo number_format((float)$canBuy, 5, '.', '');
                                break 3;
                            }
                        }
                    }
                }
            }
        }
    }
?>

