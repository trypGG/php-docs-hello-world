<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $accessToken = "747c9b3297d5640bd7ecfaa77ca1cd5d155e1bcc";
    $deviceID = "0a10aced202194944a055b7c";
    $url = "https://api.particle.io/v1/devices/$deviceID/led";

    $postData = http_build_query([
        'params' => $action,
        'access_token' => $accessToken
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);
    echo "Respuesta de Particle: " . $response;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control con PHP</title>
</head>
<body>
    <h1>Control ON/OFF con PHP</h1>
    <form method="POST">
        <button type="submit" name="action" value="on">Encender</button>
        <button type="submit" name="action" value="off">Apagar</button>
    </form>
</body>
</html>


<input type="button" onClick="switchOn()" value="ON"/>
<input type="button" onClick="switchOff()" value="OFF"/>
	
</body>
</html>
