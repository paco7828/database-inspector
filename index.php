<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database inspector</title>
</head>

<body>
    <div id="container">
        <div id="credentialsDiv">
            <form action="connect.php" method="POST">
                <h1>Enter database credentials</h1>
                <input autocomplete="off" type="text" placeholder="Server name..." name="servernameInput"><br>
                <input autocomplete="off" type="text" placeholder="Username..." name="usernameInput"><br>
                <input autocomplete="off" type="text" placeholder="Password..." name="passwordInput"><br>
                <input autocomplete="off" type="text" placeholder="Database name..." name="dbnameInput"><br>
                <input type="submit" value="Connect!">
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const inputs = document.querySelectorAll("input[type='text']");
            let index = 0;
            document.addEventListener("keydown", (e) => {
                switch (e.key) {
                    case "ArrowDown":
                        index++;
                        break;
                    case "ArrowUp":
                        index--;
                        break;
                }
                if (index < 0) {
                    index = inputs.length - 1;
                } else if (index > inputs.length - 1) {
                    index = 0;
                }
                inputs[index].focus();
            });
        });
    </script>
</body>

</html>