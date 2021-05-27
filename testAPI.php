<?php

$chi = curl_init();
$urli = "https://api.coingecko.com/api/v3/coins/{$_SESSION['xd']}/market_chart?vs_currency=eur&days=1&interval=daily";
curl_setopt($chi, CURLOPT_URL, $urli);
curl_setopt($chi, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($chi);
$priceDayBefore = json_decode($resp, true);

curl_close($chi);

echo $priceDayBefore['prices'][0][1];

// dzisiaj echo $priceDayBefore['prices'][1][1];
// wczoraj echo $priceDayBefore['prices'][0][1];

?>