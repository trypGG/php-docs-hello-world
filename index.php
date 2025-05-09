<?php
echo "Hola a todos y todas, que tengan un excelente día 21051392";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control On/Off</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script>
        var accessToken = "747c9b3297d5640bd7ecfaa77ca1cd5d155e1bcc";
        var deviceID = "0a10aced202194944a055b7c";
        var url = "https://api.particle.io/v1/devices/" + deviceID + "/led";

        function switchOn() {
            $.post(url, { params: "on", access_token: accessToken });
        }

        function switchOff() {
            $.post(url, { params: "off", access_token: accessToken });
        }
    </script>
</head>
<body>
    <h1>On/Off Control</h1>	
    <input type="button" onClick="switchOn()" value="ON"/>
    <input type="button" onClick="switchOff()" value="OFF"/>
</body>
</html>

<input type="button" onClick="switchOn()" value="ON"/>
<input type="button" onClick="switchOff()" value="OFF"/>
	
</body>
</html>
