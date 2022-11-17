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
        $data = date("Y-m-d");
        $odbior = $_POST['odbior'];
        $status = $_POST['czyOdebrano'];
        $suma = $_POST['suma'];
        $edytor = $_SESSION['login'];
        $komentarz = $_POST['comment'];
				$id_zamowienia = $_POST['id'];
        $sql = "UPDATE zamowienia SET imie = '".$imie."', nazwisko = '".$nazwisko."', telefon = '".$telefon."', data_edycji= '".$data."', data_odbioru= '".$odbior."', status_zamowienia= '".$status."', cena_suma= '".$suma."', edytor='".$edytor."', komentarz= '".$komentarz."' WHERE id_zamowienia = ".$id_zamowienia."";
        
        if (mysqli_query($polaczenie, $sql)) {
            //przesyłamy w sesji informacje, że udało się edytować zamówienie
            $_SESSION['message'] = "Edycja zamówienia o ID ".$id_zamowienia." zakończona sukcesem";
            $_SESSION['msg_type'] = "success";
            header('Location: orders.php');
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($polaczenie);
					}
					
					$sql = "DELETE FROM produkty WHERE id_zamowienia = ".$id_zamowienia."";
					if (mysqli_query($polaczenie, $sql)) {
             //przesyłamy w sesji informacje, że udało się edytować zamówienie
             $_SESSION['message'] = "Edycja zamówienia o ID ".$id_zamowienia." zakończona sukcesem";
             $_SESSION['msg_type'] = "success";
            header('Location: orders.php');
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($polaczenie);
					}

					foreach($_POST['product'] as $produkt) {

            $produkt_name = $produkt['name'];
            $produkt_quantity  = $produkt['quantity'];
            $produkt_price  = $produkt['price'];
          
            $sql = "INSERT INTO produkty (produkt, ilosc, cena, id_zamowienia) VALUES ('$produkt_name', '$produkt_quantity', '$produkt_price', '$id_zamowienia')";

            if (mysqli_query($polaczenie, $sql)) {
                //przesyłamy w sesji informacje, że udało się edytować zamówienie
                $_SESSION['message'] = "Edycja zamówienia o ID ".$id_zamowienia." zakończona sukcesem";
                $_SESSION['msg_type'] = "success";
                header('Location: orders.php');
              } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($polaczenie);
              }
          
          }
    
          
		$polaczenie->close();
	}
  
?>