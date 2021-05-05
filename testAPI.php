<?php
/*
$ch = curl_init();

$url = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=eur&order=market_cap_desc&per_page=20&page=1&sparkline=false";

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

$response = curl_exec($ch);

if($e = curl_error($ch)){
    echo $e;
}else{
    $decoded = json_decode($response,true);
    print_r($decoded);
}
//print_r($decoded['1']['name']);
curl_close($ch);
*/

echo date("H:i");
echo date("Y-m-d");
?>
