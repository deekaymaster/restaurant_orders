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
		    $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $telefon = $_POST['phone'];
        $odbior = $_POST['odbior'];
        $data = date("Y-m-d");
        $status = 'nieodebrano';
        $suma = $_POST['suma'];
        $autor = $_SESSION['login'];
        $komentarz = $_POST['comment'];

        $sql = "INSERT INTO zamowienia (imie, nazwisko, telefon, data_zamowienia, data_odbioru, status_zamowienia, cena_suma, autor, komentarz) VALUES ('$imie', '$nazwisko', '$telefon', '$data', '$odbior', '$status', '$suma', '$autor', '$komentarz')";
        
        $last_id;

        if (mysqli_query($polaczenie, $sql)) {
            $last_id = $polaczenie->insert_id; //jest to id jakie dostało dodane przez nas zamowienie
            //przesyłamy w sesji informacje, że udało się dodać zamówienie
            $_SESSION['message'] = "Dodano zamówienie o ID ".$last_id."";
            $_SESSION['msg_type'] = "success";
            header('Location: orders.php');
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($polaczenie);
          }
        
        
        foreach($_POST['product'] as $produkt) {

            $produkt_name = $produkt['name'];
            $produkt_quantity  = $produkt['quantity'];
            $produkt_price  = $produkt['price'];
          
            $sql = "INSERT INTO produkty (produkt, ilosc, cena, id_zamowienia) VALUES ('$produkt_name', '$produkt_quantity', '$produkt_price', '$last_id')";

            if (mysqli_query($polaczenie, $sql)) {
                //przesyłamy w sesji informacje, że udało się dodać zamówienie
                $_SESSION['message'] = "Dodano zamówienie o ID ".$last_id."";
                $_SESSION['msg_type'] = "success";
                header('Location: orders.php');
              } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($polaczenie);
              }
          
          }
          
		$polaczenie->close();
	}
  
?>