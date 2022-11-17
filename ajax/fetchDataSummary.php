<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: ../index.php');
		exit();
	}
    
    require_once "../connect.php";

    $con = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($con->connect_errno!=0)
	{
		echo "Error: ".$con->connect_errno;
	}
	else
    {
        $output= array();
        $sqlFirst = "SELECT * FROM lista_produktow ";
        
        $totalQuery = mysqli_query($con,$sqlFirst);
        $total_all_rows = mysqli_num_rows($totalQuery);
        //WYSZUKIWANIE(imie, nazwisko)
        if(isset($_POST['search']['value']))
        {
            $search_value = $_POST['search']['value'];
            $sqlFirst .= " WHERE nazwa_produktu like '%".$search_value."%'";
        }
        //SORTOWANIE
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column']+1;
            $order = $_POST['order'][0]['dir'];
            $sqlFirst .= " ORDER BY ".$column_name." ".$order."";
        }
        else
        {
           $sqlFirst .= " ORDER BY id_produktu desc";
        }
        //ILE REKORDÓW POKAZAĆ
        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $sqlFirst .= " LIMIT  ".$start.", ".$length;
        }	
        
        $query = mysqli_query($con,$sqlFirst);
        $count_rows = mysqli_num_rows($query);
        $data = array();
        while($row = mysqli_fetch_assoc($query))
        {
            $sqlSecond = "SELECT ilosc, cena FROM produkty WHERE produkt='".$row['nazwa_produktu']."'";
            $ilosc=0;
            $cena=0;
            $querySecond = mysqli_query($con,$sqlSecond);
            while($rowSecond = mysqli_fetch_assoc($querySecond))
            {
                $ilosc = $ilosc + $rowSecond['ilosc'];
                $cena = $cena + $rowSecond['cena'];
            }
            //zaokraglamy do 1 miejsca po przecinku
            $ilosc = round($ilosc,1);
            $cena = round($cena,1);
            
            $sub_array = array();
            $sub_array[] = '<span class="fw-bold">'.$row['id_produktu'].'</span>';
            $sub_array[] = $row['nazwa_produktu'];
            $sub_array[] = $ilosc;
            $sub_array[] = $cena.' PLN';
        
            $data[] = $sub_array;
        }
        
        $output = array(
            'draw'=> intval($_POST['draw']),
            'recordsTotal' =>$count_rows ,
            'recordsFiltered'=>$total_all_rows,
            'data'=>$data,
        );
        echo json_encode($output);     
    }
?>