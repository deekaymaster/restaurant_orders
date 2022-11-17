<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: ../index.php');
		exit();
	}
    
    require_once "../connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
    {
		$id = $_POST["id_produktu"];
        $wynik = mysqli_query($polaczenie,"SELECT cena_produktu FROM lista_produktow WHERE id_produktu=".$id."");
        $cena;
        while($row = mysqli_fetch_array($wynik))
        {
            $cena = $row['cena_produktu'];
        }
        $value =  array('price' => $cena);
        echo json_encode($value);
        $polaczenie->close();
	}

?>