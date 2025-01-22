<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $route = isset($_POST['route']) ? trim($_POST['route']) : 'user/set/data/';
        $username = isset($_POST['uname']) ? trim($_POST['uname']) : '';
        $forename = isset($_POST['fname']) ? trim($_POST['fname']) : '';
        $surename = isset($_POST['lname']) ? trim($_POST['lname']) : '';


        // Ziel-URL
        // $url = 'https://localhost/Stream/unsere_erste_api/api/?route=user/set/data/';
        $url =  $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_ADDR'] . str_replace('index', 'api/index', $_SERVER['REQUEST_URI']) . '?route=' . $route;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer HALLO_ICH_BIN_DER_ACCESS_TOKEN', // Falls erforderlich
        ];

        // Daten, die gesendet werden sollen
        $data = [
            'username' => $username,
            'forename' => $forename,
            'surename' => $surename
        ];

        // CURL initialisieren
        $ch = curl_init();

        // CURL-Optionen setzen
        curl_setopt($ch, CURLOPT_URL, $url); // Ziel-URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Antwort als String zurückgeben
        // curl_setopt($ch, CURLOPT_POST, true); // POST-Methode
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // HTTP-Methode (GET, POST, PUT, DELETE)
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // JSON-Daten senden
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // SSL-Host-Überprüfung deaktivieren
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL-Zertifikatsprüfung deaktivieren
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Zeitbegrenzung  0=Keine Zeitbegrenzung (in Sekunden)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Header setzen

        // Anfrage ausführen
        $response = curl_exec($ch);

        // Fehlerbehandlung
        if (curl_errno($ch)) {
            throw new Exception("cURL error: " . curl_error($ch));
        }
        // HTTP-Statuscode abrufen
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
} catch (Exception $e) {
    echo 'Fehler: ' . $e->getMessage();
} finally {
    $ch = null; // Explizite Freigabe des Handles
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erste API</title>
    <style>
        hr {
            width: 100% !important;
        }

        form,
        h1 {
            margin: 10px auto;
            width: 400px;
            text-align: center;
        }
    </style>

</head>

<body style="background-color: #666666; color: #fefefe; font-family: monospace; font-size: 1.2rem;">
    <h1>Unsere erste API</h1>
    <hr>
    <form action="#" method="POST">
        <label for="route">Route:</label><br>
        <input type="text" id="route" name="route" value="<?php echo $route ?? 'user/set/data/'; ?>"><br>
        <label for="uname">User name:</label><br>
        <input type="text" id="uname" name="uname" value="<?php echo $username ?? 'Superuser'; ?>"><br>
        <label for="fname">Fore name:</label><br>
        <input type="text" id="fname" name="fname" value="<?php echo $forename ?? 'John'; ?>"><br>
        <label for="lname">Sure name:</label><br>
        <input type="text" id="lname" name="lname" value="<?php echo $surename ?? 'Doe'; ?>"><br><br>
        <input type="submit" value="Submit">
    </form>
    <hr>
    <pre><?php
            $http_code = isset($http_code) ? $http_code : '---';
            echo 'HTTP-Statuscode: ' . $http_code . '<hr><br><br>';

            $response = isset($response) ? $response : '---';
            echo 'Json response:<br>' . $response . '<br><br><br>';
            echo 'Array response:<br>';
            print_r(json_decode($response ?? '{}', true));
            ?>
    </pre>

    <hr>
    <div style="max-width: 90%; margin: 10px auto; overflow: hidden;">
        <?php
        // phpinfo();
        ?>
    </div>

</body>

</html>