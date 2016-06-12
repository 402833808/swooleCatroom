<?php
    session_start();
    if(!isset($_SESSION["userInfo"])) {
        if (isset($_POST["userName"]) && !is_null($_POST["userName"])) {
            $_SESSION["userInfo"]["name"] = $_POST["userName"];
        } else {
            header("location:login.php");
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="UTF-8">
    <script type="text/javascript">
        var exampleSocket = new WebSocket("ws://127.0.0.1:9501");
        exampleSocket.onopen = function (event) {
            exampleSocket.send("{<?php echo $_SESSION["userInfo"]["name"];?>}");
        };
        exampleSocket.onmessage = function (event) {
            var html = document.getElementById("sendContent").innerHTML;
            document.getElementById("sendContent").innerHTML= html + event.data+"<br>";
        }
    </script>
</head>
<body>
<div id="sendContent">

</div>
<input  type="text" id="content">
<button  onclick="exampleSocket.send( document.getElementById('content').value )">发送</button>
</body>
</html>
