<?php

	session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: orderPanel.php');
		exit();
	}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>AGAPE</title>
    <style>
    input[type="text"] {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    input[type="password"] {
        border-bottom-left-radius: 0px;
        border-bottom-right-radius: 0px;
    }
    </style>
</head>

<body>
    <div class="text-center mt-5">
        <form style="max-width:300px;margin:auto;" action="login.php" method="POST">
            <img class="mt-4 mb-4" src="images/logo.png" height="72" alt="logo agape">
            <h1 class="h3 mb-3 font-weight-normal">Panel logowania</h1>
            <label for="login" class="sr-only">Login</label>
            <input type="text" id="login" name="login" class="form-control" placeholder="login" required autofocus>
            <label for="haslo" class="sr-only">Hasło</label>
            <input type="password" id="haslo" name="haslo" class="form-control" placeholder="hasło" required>
            <div class="mt-3">
                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Zaloguj" />
            </div>
        </form>
        <?php
	        if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
        ?>
    </div>
</body>

</html>