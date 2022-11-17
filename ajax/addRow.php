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
		$x = $_POST["x"];
        echo '<div class="input-group mb-3 d-flex justify-content-between">';
        echo '<select name="product['.$x.'][name]" class="col-5 selectProductName" required>';
        echo '<option value="">--Wybierz zestaw--</option>';
        
        $wynik = mysqli_query($polaczenie,"SELECT * FROM lista_produktow ORDER BY nazwa_produktu ASC");

        while($row = mysqli_fetch_array($wynik))
        {
            echo '<option name="'.$row['id_produktu'].'" value="'.$row['nazwa_produktu'].'">'.$row['nazwa_produktu'].'</option>';
        }
        echo '</select>';

        echo '<input name="product['.$x.'][quantity]" type="number" class="col-2 quantityClass" min="0" step="0.001" readonly />';
		echo '<input name="product['.$x.'][price]" type="number" class="col-2 priceClass" readonly/>';
        echo '<div class="input-group-append col-2">';
		echo '<button id="removeRow" type="button" class="btn btn-danger remove_field">Usuń</button>';
		echo '</div>';
		echo '</div>';
	}
  
?>