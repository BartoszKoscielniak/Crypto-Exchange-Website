<?php
session_start();
require_once "dataBaseConnector.php";

if (!isset($_SESSION['isLoggedIn'])) {
    header('Location: homePage.php');
    exit();
}
//pobranie informacji o krypto
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

    if ($connection->connect_errno != 0) {
        throw new Exception(mysqli_connect_error());
    } else {
        for ($i = 0; $i < sizeof($decoded); $i++) {

            $result = $connection->query("SELECT COUNT(*) FROM kryptowaluty WHERE nazwa = '" . $decoded[$i]['name'] . "'");
            $row = $result->fetch_assoc();
            $result->free();

            $result = $connection->query("SELECT MAX(id_krypto) FROM kryptowaluty");
            $max = $result->fetch_assoc();
            $result->free();

            if ($row['COUNT(*)'] == 0) {
                $connection->query("INSERT INTO kryptowaluty VALUES (" . ($max['MAX(id_krypto)'] + 1) . ",'" . $decoded[$i]['name'] . "','" . $decoded[$i]['current_price'] . "')");
            } else {
                $connection->query("UPDATE kryptowaluty SET kurs = '" . $decoded[$i]['current_price'] . "' WHERE nazwa = '" . $decoded[$i]['name'] . "'");
            }
        }
    }
}
curl_close($ch);

//sprawdzanie ktore krypto posiadamy
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_error());
} else {
    $result = $connection->query("SELECT * FROM portfele WHERE id_użytkownika = '" . $_SESSION['id_użytkownika'] . "'");
    $_SESSION['portfel'] = $result->fetch_all();
    $result->free();

    $result = $connection->query("SELECT * FROM lista_walut WHERE id_portfela = '" . $_SESSION['portfel'][0][0] . "'");
    $_SESSION['lista_walut'] = $result->fetch_all();
    $result->free();
}

//pobranie listy krypto dostepnej w bazie
if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_error());
} else {
    $result = $connection->query("SELECT * FROM kryptowaluty ");
    $_SESSION['krypto'] = $result->fetch_all();
    $result->free();
}
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Octopus Exchange</title>
    <!-- bootstrap 5 css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <!-- custom css -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="userProfileStyle.css">
</head>

<body>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="bootstrap" viewBox="0 0 118 94">
            <title>Bootstrap</title>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z"></path>
        </symbol>
        <symbol id="home" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5z"></path>
        </symbol>
        <symbol id="speedometer2" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.389.389 0 0 0-.029-.518z"></path>
            <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3z"></path>
        </symbol>
        <symbol id="table" viewBox="0 0 16 16">
            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm15 2h-4v3h4V4zm0 4h-4v3h4V8zm0 4h-4v3h3a1 1 0 0 0 1-1v-2zm-5 3v-3H6v3h4zm-5 0v-3H1v2a1 1 0 0 0 1 1h3zm-4-4h4V8H1v3zm0-4h4V4H1v3zm5-3v3h4V4H6zm4 4H6v3h4V8z"></path>
        </symbol>
        <symbol id="people-circle" viewBox="0 0 16 16">
            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
        </symbol>
        <symbol id="grid" viewBox="0 0 16 16">
            <path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5v-3zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5v-3zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"></path>
        </symbol>
        <symbol id="collection" viewBox="0 0 16 16">
            <path d="M2.5 3.5a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-11zm2-2a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zm1.5.5A.5.5 0 0 1 1 13V6a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-13z"></path>
        </symbol>
        <symbol id="calendar3" viewBox="0 0 16 16">
            <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"></path>
            <path d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
        </symbol>
        <symbol id="chat-quote-fill" viewBox="0 0 16 16">
            <path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM7.194 6.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"></path>
        </symbol>
        <symbol id="cpu-fill" viewBox="0 0 16 16">
            <path d="M6.5 6a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3z"></path>
            <path d="M5.5.5a.5.5 0 0 0-1 0V2A2.5 2.5 0 0 0 2 4.5H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2v1H.5a.5.5 0 0 0 0 1H2A2.5 2.5 0 0 0 4.5 14v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14h1v1.5a.5.5 0 0 0 1 0V14a2.5 2.5 0 0 0 2.5-2.5h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14v-1h1.5a.5.5 0 0 0 0-1H14A2.5 2.5 0 0 0 11.5 2V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5a.5.5 0 0 0-1 0V2h-1V.5zm1 4.5h3A1.5 1.5 0 0 1 11 6.5v3A1.5 1.5 0 0 1 9.5 11h-3A1.5 1.5 0 0 1 5 9.5v-3A1.5 1.5 0 0 1 6.5 5z"></path>
        </symbol>
        <symbol id="gear-fill" viewBox="0 0 16 16">
            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"></path>
        </symbol>
        <symbol id="speedometer" viewBox="0 0 16 16">
            <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"></path>
            <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"></path>
        </symbol>
        <symbol id="toggles2" viewBox="0 0 16 16">
            <path d="M9.465 10H12a2 2 0 1 1 0 4H9.465c.34-.588.535-1.271.535-2 0-.729-.195-1.412-.535-2z"></path>
            <path d="M6 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 1a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm.535-10a3.975 3.975 0 0 1-.409-1H4a1 1 0 0 1 0-2h2.126c.091-.355.23-.69.41-1H4a2 2 0 1 0 0 4h2.535z"></path>
            <path d="M14 4a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"></path>
        </symbol>
        <symbol id="tools" viewBox="0 0 16 16">
            <path d="M1 0L0 1l2.2 3.081a1 1 0 0 0 .815.419h.07a1 1 0 0 1 .708.293l2.675 2.675-2.617 2.654A3.003 3.003 0 0 0 0 13a3 3 0 1 0 5.878-.851l2.654-2.617.968.968-.305.914a1 1 0 0 0 .242 1.023l3.356 3.356a1 1 0 0 0 1.414 0l1.586-1.586a1 1 0 0 0 0-1.414l-3.356-3.356a1 1 0 0 0-1.023-.242L10.5 9.5l-.96-.96 2.68-2.643A3.005 3.005 0 0 0 16 3c0-.269-.035-.53-.102-.777l-2.14 2.141L12 4l-.364-1.757L13.777.102a3 3 0 0 0-3.675 3.68L7.462 6.46 4.793 3.793a1 1 0 0 1-.293-.707v-.071a1 1 0 0 0-.419-.814L1 0zm9.646 10.646a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708zM3 11l.471.242.529.026.287.445.445.287.026.529L5 13l-.242.471-.026.529-.445.287-.287.445-.529.026L3 15l-.471-.242L2 14.732l-.287-.445L1.268 14l-.026-.529L1 13l.242-.471.026-.529.445-.287.287-.445.529-.026L3 11z"></path>
        </symbol>
        <symbol id="chevron-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"></path>
        </symbol>
        <symbol id="geo-fill" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999zm2.493 8.574a.5.5 0 0 1-.411.575c-.712.118-1.28.295-1.655.493a1.319 1.319 0 0 0-.37.265.301.301 0 0 0-.057.09V14l.002.008a.147.147 0 0 0 .016.033.617.617 0 0 0 .145.15c.165.13.435.27.813.395.751.25 1.82.414 3.024.414s2.273-.163 3.024-.414c.378-.126.648-.265.813-.395a.619.619 0 0 0 .146-.15.148.148 0 0 0 .015-.033L12 14v-.004a.301.301 0 0 0-.057-.09 1.318 1.318 0 0 0-.37-.264c-.376-.198-.943-.375-1.655-.493a.5.5 0 1 1 .164-.986c.77.127 1.452.328 1.957.594C12.5 13 13 13.4 13 14c0 .426-.26.752-.544.977-.29.228-.68.413-1.116.558-.878.293-2.059.465-3.34.465-1.281 0-2.462-.172-3.34-.465-.436-.145-.826-.33-1.116-.558C3.26 14.752 3 14.426 3 14c0-.599.5-1 .961-1.243.505-.266 1.187-.467 1.957-.594a.5.5 0 0 1 .575.411z"></path>
        </symbol>
    </svg>

    <nav class="navbar navbar-expand d-flex flex-column align-item-start" id="sidebar">
        <a href="#" class="navbar-brand text-light mt-5">

            <img src="img/oct.png" style="width: 240px; height: 170px; margin-left:3%" alt="niema">
        </a>
        <ul class="navbar-nav d-flex flex-column mt-5 w-100">
            <li class="nav-item w-100">
                <a href="home.php" class="nav-link text-light pl-4"><img src="img/home.png"> Home</a>
            </li>
            <li class="nav-item w-100">
                <a href="wallet.php" class="nav-link text-light pl-4"><img src="img/wallet.png"> Wallet</a>
            </li>
            <li class="nav-item w-100">
                <a href="buyOrSell.php" class="nav-link text-light pl-4"><img src="img/buy.png"> Buy/Sell</a>
            </li>
            
            <li class="nav-item w-100">
                <a href="history.php" class="nav-link text-light pl-4"><img src="img/history.png"> History</a>
            </li>
            <li class="nav-item w-100" data-toggle="modal" data-target="#myContactModal">
                <a href="#" class="nav-link text-light pl-4"><img src="img/post.png"> Contact Us</a>
            </li>
        </ul>
    </nav>

    <!-- Home section -->

    <section id="home" class="p-4 my-container">
        <div style="display: inline">
            <script src="https://widgets.coingecko.com/coingecko-coin-price-marquee-widget.js"></script>
            <coingecko-coin-price-marquee-widget coin-ids="bitcoin,ethereum,litecoin,ripple" currency="eur" background-color="#ffffff" locale="en"></coingecko-coin-price-marquee-widget>
            <h2>Home</h2>

            <form action="/20-21-ai-projekt-lab3-projekt-ai-koscielniak-b-matusik-l/logOut.php">
            

            <button type="submit" class="btn btn-outline-danger" data-mdb-ripple-color="dark" style=" float:right; margin-right:10px">
                    Log Out
                </button>
                <button type="button" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark" style="float:right; margin-right:10px" data-target="#myModal" data-toggle="modal">
                    Buy/Sell
                </button>
            </form>

        </div>

        <div class="container">

            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <!--Modal: Login / Register Form-->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog cascading-modal" role="document">
                    <!--Content-->
                    <div class="modal-content">

                        <!--Modal cascading tabs-->
                        <div class="modal-c-tabs">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs md-tabs tabs-2 light-blue darken-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#panel7" role="tab"><i class="fas fa-user mr-1"></i>
                                        Buy</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#panel8" role="tab"><i class="fas fa-user-plus mr-1"></i>
                                        Sell</a>
                                </li>
                            </ul>

                            <!-- Tab panels -->
                            <div class="tab-content">
                                <!--Buy tab-->
                                <div class="tab-pane fade in show active" id="panel7" role="tabpanel">

                                    <!--Body-->
                                    <div class="modal-body mb-1">
                                        <div class="md-form form-sm mb-5">
                                            <i class="fas fa-envelope prefix"></i>
                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                            <h1 id="euro-amount">
                                                <?php
                                                echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                                ?>
                                                <a style="font-size: 20px;">€</a>
                                            </h1>
                                        </div>

                                        <div class="md-form form-sm mb-4">
                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Buy:</label>

                                            <form id="buyForm" action="buyCrypto.php" method="post" class="mb-3">
                                                <select name="buy" id="toBuy" class="form-select" aria-label="Default select example" >

                                                    <?php
                                                    for ($i = 0; $i < 10; $i++) {
                                                        echo '<option value="' . htmlspecialchars($_SESSION['krypto'][$i][1]) . '" >' . $_SESSION['krypto'][$i][1] . '</option>' . "\n";
                                                    }
                                                    ?>

                                                </select>
                                                <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Pay:</label>
                                                <select name="pay" id="toPay" class="form-select" aria-label="Default select example" >

                                                    <option id="myWallet" value="myWallet">My Wallet</option>
                                                    <?php
                                                    for ($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                                                        for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                                                            if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0] && $_SESSION['lista_walut'][$i][3] > 0.0001) {
                                                                echo '<option id="' . $_SESSION['lista_walut'][$i][2] . '" value="' . htmlspecialchars($_SESSION['krypto'][$a][1]) . '" >' . $_SESSION['krypto'][$a][1] . '(' . $_SESSION['lista_walut'][$i][3] . ')</option>' . "\n";
                                                                break;
                                                            }
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                                <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">How much?</label>
                                                <div class="input-group mb-3">
                                                    <input id="amountInputBuy" name="amount" type="text" class="form-control" onkeypress="return onlyNumberKey(event)" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button id="maxButtonBuy" class="btn btn-outline-primary" type="button" onclick="sendMaxBuy()">MAX</button>
                                                    </div>
                                                </div>
                                                <?php if (isset($_SESSION['err_fund'])) {
                                                    echo $_SESSION['err_fund'];
                                                    unset($_SESSION['err_fund']);
                                                } ?>
                                                <textarea name="area" style="display:none">home.php</textarea>
                                                <button id="submitButton" type="submit" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark">Buy</button>
                                            </form>

                                        </div>
                                    </div>
                                    <!--Footer-->
                                    <div class="modal-footer">
                                        <p>Powered by</p>

                                        <img src="img/mastercard.png" width="60px" height="60px">
                                        <button type="button" class="btn btn-outline-primary btn-rounded" data-dismiss="modal" onclick="clearInput()">Close</button>
                                    </div>
                                </div>
                                <!--/.Buy tab-->

                                <!--Sell tab-->
                                <div class="tab-pane fade" id="panel8" role="tabpanel">

                                    <div class="modal-body">
                                        <div class="md-form form-sm mb-5">
                                            <i class="fas fa-envelope prefix"></i>

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Your wallet</label>
                                            <h1 id="euro-amount">
                                                <?php
                                                echo "<script>console.log('Debug Objects:');</script>";
                                                echo '<a>' . number_format($_SESSION['portfel'][0][2],2) . '</a>'
                                                ?>
                                                <a style="font-size: 20px;">€</a>
                                            </h1>

                                            <i class="fas fa-lock prefix"></i>

                                        </div>

                                        <div>

                                            <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">Sell</label>

                                            <form action="sellCrypto.php" method="post" class="mb-3">

                                                <select id="toSell" name="sell" class="form-select" aria-label="Default select example">

                                                    <?php
                                                    $temp = 0;
                                                    for ($i = 0; $i < sizeof($_SESSION['lista_walut']); $i++) {
                                                        for ($a = 0; $a < sizeof($_SESSION['krypto']); $a++) {
                                                            if ($_SESSION['lista_walut'][$i][2] == $_SESSION['krypto'][$a][0] && $_SESSION['lista_walut'][$i][3] > 0.0001) {
                                                                echo '<option id="' . $_SESSION['lista_walut'][$i][3] . '" value="' . htmlspecialchars($_SESSION['krypto'][$a][0]) . '" >' . $_SESSION['krypto'][$a][1] . '(' . $_SESSION['lista_walut'][$i][3] . ')</option>' . "\n";
                                                                $temp += 1;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if ($temp == 0) {
                                                        echo "No assets to sell";
                                                    }
                                                    ?>
                                                </select>

                                                <label data-error="wrong" data-success="right" for="modalLRInput12" class="wallet-val">How much?</label>

                                                <div class="input-group mb-3">
                                                    <input id="amountInputSell" name="amount" type="text" class="form-control" onkeypress="return onlyNumberKey(event)" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button id="maxButton" class="btn btn-outline-primary" type="button" onclick="sendMax()">MAX</button>
                                                    </div>
                                                </div>

                                                <?php if (isset($_SESSION['err_fund2'])) {
                                                    echo $_SESSION['err_fund2'];
                                                    unset($_SESSION['err_fund2']);
                                                } ?>
                                                <textarea name="area" style="display:none">home.php</textarea>
                                                <button id="submitButtonSell" type="submit" class="btn btn-outline-primary btn-rounded" data-mdb-ripple-color="dark">Sell</button>
                                            </form>
                                        </div>

                                    </div>
                                    <!--Footer-->
                                    <div class="modal-footer">
                                        <p>Powered by</p>
                                        <img src="img/mastercard.png" width="60px" height="60px">
                                        <button type="button" class="btn btn-outline-primary btn-rounded" data-dismiss="modal" onclick="clearInput()">Close</button>
                                    </div>
                                </div>
                                <!--/.Sell tab-->
                            </div>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>

        </div>

        <div class="container">

            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <!--Modal: Login / Register Form-->
            <div class="modal fade" id="myContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog cascading-modal" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <div class="modal-body mb-1">

                            <form>

                                <h2>Contact Us</h2>

                                <input id="amountInputSell" name="amount" type="text" class="form-control" placeholder="Name"><br>
                                <input id="amountInputSell" name="amount" type="text" class="form-control" placeholder="Email"><br>

                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4" placeholder="Message"></textarea>

                                <!--Footer-->
                                <div class="modal-footer">
                                    <button style="width:70px" type="submit" class="btn btn-outline-success">Send</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <button class="btn my-4" id="menu-btn">Menu</button>

        <div id="main-screen">

            <div style="height:560px; background-color: #FFFFFF; overflow:hidden; box-sizing: border-box; border: 1px solid #56667F; border-radius: 4px; text-align: right; line-height:14px; font-size: 12px; font-feature-settings: normal; text-size-adjust: 100%; box-shadow: inset 0 -20px 0 0 #56667F;padding:1px;padding: 0px; margin: 0px; width: 100%;">
                <div style="height:540px; padding:0px; margin:0px; width: 100%;"><iframe src="https://widget.coinlib.io/widget?type=chart&theme=light&coin_id=859&pref_coin_id=1506" width="100%" height="536px" scrolling="auto" marginwidth="0" marginheight="0" frameborder="0" border="0" style="border:0;margin:0;padding:0;line-height:14px;"></iframe></div>
                <div style="color: #FFFFFF; line-height: 14px; font-weight: 400; font-size: 11px; box-sizing: border-box; padding: 2px 6px; width: 100%; font-family: Verdana, Tahoma, Arial, sans-serif;"><a href="https://coinlib.io" target="_blank" style="font-weight: 500; color: #FFFFFF; text-decoration:none; font-size:11px">Cryptocurrency Prices</a>&nbsp;by Coinlib
                </div>
            </div>

    </section>

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.min.js" integrity="sha384-5h4UG+6GOuV9qXh6HqOLwZMY4mnLPraeTrjT5v07o347pj6IkfuoASuGBhfDsp3d" crossorigin="anonymous"></script>
    <!-- custom js -->
    <script>
        var menu_btn = document.querySelector("#menu-btn")
        var sidebar = document.querySelector("#sidebar")
        var container = document.querySelector(".my-container")
        menu_btn.addEventListener("click", () => {
            sidebar.classList.toggle("active-nav")
            container.classList.toggle("active-cont")
        })

        function onlyNumberKey(evt) {
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57) && ASCIICode != 46) {
                validateBuy();
                return false;
            } else {
                validateBuy();
                return true;
            }
        }

        function validateBuy() {
            var input = document.getElementById('amountInputBuy').value;
            const button = document.getElementById('submitButton');

            if(input > 0 & document.getElementById('toBuy').value != document.getElementById('toPay').value){
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        }
        setInterval(validateBuy, 250);

        function validateSell() {
            var input = document.getElementById('amountInputSell').value;
            const button = document.getElementById('submitButtonSell');

            if (input > 0) {
                button.disabled = false;
            } else {
                button.disabled = true;
            }
        }
        setInterval(validateSell, 250);

        function sendMax(max){
            var e =document.getElementById('toSell');
            document.getElementById('amountInputSell').value = e.options[e.selectedIndex].id;
        }

        function sendMaxBuy(max){
            const xhr = new XMLHttpRequest();

            xhr.onload = function (){

                const serverResponse = document.getElementById("amountInputBuy").value = this.responseText ;
                const serverResponse1 = document.getElementById("serverResponse");
                serverResponse1.innerHTML = this.responseText;
            };
            var pay = document.getElementById('toPay');
            var buy = document.getElementById('toBuy');
            xhr.open("POST","howMuch.php");
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send('pay=' + pay.options[pay.selectedIndex].value + '&buy=' + buy.options[buy.selectedIndex].value);
        }

        function clearInput(){
            document.getElementById('amountInputBuy').value = '';
            document.getElementById('amountInputSell').value = '';
        }

    </script>
</body>

</html>