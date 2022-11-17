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
        $sql = "SELECT * FROM zamowienia ";
        
        $totalQuery = mysqli_query($con,$sql);
        $total_all_rows = mysqli_num_rows($totalQuery);
        //WYSZUKIWANIE(imie, nazwisko)
        if(isset($_POST['search']['value']))
        {
            $search_value = $_POST['search']['value'];
            $sql .= " WHERE imie like '%".$search_value."%'";
            $sql .= " OR nazwisko like '%".$search_value."%'";
        }
        //SORTOWANIE
        if(isset($_POST['order']))
        {
            $column_name = $_POST['order'][0]['column']+1;
            $order = $_POST['order'][0]['dir'];
            $sql .= " ORDER BY ".$column_name." ".$order."";
        }
        else
        {
           $sql .= " ORDER BY id_zamowienia desc";
        }
        //ILE REKORDÓW POKAZAĆ
        if($_POST['length'] != -1)
        {
            $start = $_POST['start'];
            $length = $_POST['length'];
            $sql .= " LIMIT  ".$start.", ".$length;
        }	
        
        $query = mysqli_query($con,$sql);
        $count_rows = mysqli_num_rows($query);
        $data = array();
        while($row = mysqli_fetch_assoc($query))
        {
            $sub_array = array();
            $sub_array[] = '<span class="fw-bold">'.$row['id_zamowienia'].'</span>';
            $sub_array[] = $row['imie'];
            $sub_array[] = $row['nazwisko'];
            $sub_array[] = $row['telefon'];
            $sub_array[] = $row['data_odbioru'];

            if($row['status_zamowienia']=="nieodebrano")
            {
                $sub_array[] = '<span class="status text-warning" style="margin:0;padding:0;">&bull;</span>'.$row['status_zamowienia'].'';
            }else{
                $sub_array[] = '<span class="status text-success" style="margin:0;padding:0;">&bull;</span>'.$row['status_zamowienia'].'';
            }

            $sub_array[] = '<span>'.$row['cena_suma'].' PLN</span>';
            $sub_array[] = '<div class="d-flex flex-row justify-content-between"><form action="edit.php" method="GET"><input type="hidden" name="id_zamowienia" value="'.$row['id_zamowienia'].'"/><button id="btnEdit" class="btn btn-info btn-md btn-block" type="submit">Szczegóły</button></form><input type="hidden" class="id_zamowienia" value="'.$row['id_zamowienia'].'"/><button class="delete_btn btn btn-danger btn-md btn-block" type="submit">Usuń</button></div>';

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