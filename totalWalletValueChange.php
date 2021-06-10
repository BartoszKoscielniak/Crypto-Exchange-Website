<?php
    session_start();

    $ch = curl_init();
    $url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=eur&order=market_cap_desc&per_page=100&page=1&sparkline=false";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    if ($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($response, true);
    }
    curl_close($ch);

    //liczenie zmiany wartosci portfela
    $_SESSION['totalWalletValue'] = 0;
    $_SESSION['yesterdaysTotalValueDifference'] = 0;
    for ($u = 0; $u < sizeof($_SESSION['lista_walut']); $u++){
        for ($g = 0; $g < sizeof($_SESSION['krypto']); $g++){
            if ($_SESSION['lista_walut'][$u][1] == $_SESSION['portfel'][0][0] && $_SESSION['lista_walut'][$u][2] == $_SESSION['krypto'][$g][0] && $_SESSION['lista_walut'][$u][3] > 0){
                $_SESSION['totalWalletValue'] = $_SESSION['totalWalletValue'] + $_SESSION['lista_walut'][$u][3] * $_SESSION['krypto'][$g][2];

                for ($next = 0; $next < sizeof($decoded); $next++) {
                    if ($_SESSION['krypto'][$g][1] == $decoded[$next]['name']) {
                        $chi = curl_init();
                        $urli = "https://api.coingecko.com/api/v3/coins/{$decoded[$next]['id']}/market_chart?vs_currency=eur&days=1&interval=daily";
                        curl_setopt($chi, CURLOPT_URL, $urli);
                        curl_setopt($chi, CURLOPT_RETURNTRANSFER, true);
                        $resp = curl_exec($chi);
                        $priceDayBefore = json_decode($resp, true);

                        curl_close($chi);
                        if (curl_errno($chi) != 0){
                            echo curl_errno($chi);
                        }
                        $_SESSION['yesterdaysTotalValueDifference'] = $_SESSION['yesterdaysTotalValueDifference'] + $_SESSION['lista_walut'][$u][3] * $priceDayBefore['prices'][0][1];
                        break 2;
                    }
                }
            }
        }
    }

    $_SESSION['yesterdaysTotalValueDifference'] = $_SESSION['totalWalletValue'] - $_SESSION['yesterdaysTotalValueDifference'];

    if ($_SESSION['yesterdaysTotalValueDifference'] >= 0){
        $_SESSION['yesterdaysTotalValueDifference'] = '<h5 style="color: green">Yesterdays Value Change: +'.number_format($_SESSION['yesterdaysTotalValueDifference'],2).'€</h5>';
    }else{
        $_SESSION['yesterdaysTotalValueDifference'] = '<h5 style="color: red">Yesterdays Value Change:</h5> '.number_format($_SESSION['yesterdaysTotalValueDifference'],2).'€</h5>';
    }

    if ($_POST['pay'] == 1){
        echo $_SESSION['yesterdaysTotalValueDifference'];
    }else{
        echo "Total value: ".number_format($_SESSION['totalWalletValue'],2)."€";
    }

?>