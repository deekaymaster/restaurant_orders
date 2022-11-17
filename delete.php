<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}

    require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$id_zamowienia = $_POST['id_zamowienia'];
		//usuwamy wszystkie produkty o id_zamowienia
        $sql_first = "DELETE FROM produkty WHERE id_zamowienia=".$id_zamowienia."";
        $sql_second = "DELETE FROM zamowienia WHERE id_zamowienia=".$id_zamowienia."";

        
        if (mysqli_query($polaczenie, $sql_first) && mysqli_query($polaczenie, $sql_second)) {
			//przesyłamy w sesji informacje, że udało się usunąć zamówienie
            $_SESSION['message'] = "Usunięto zamówienie o ID ".$id_zamowienia."";
			$_SESSION['msg_type'] = "danger";
			header('Location: orders.php');
          } else {
            echo "Error: " . $sql_first . " ". $sql_second ."<br>" . mysqli_error($polaczenie);
          }
          
		$polaczenie->close();
	}
  
?>