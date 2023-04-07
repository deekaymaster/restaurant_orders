<?php 

session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}

    require_once "connect.php";
    require_once "libs/TCPDF-main/tcpdf.php";  
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
                $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $obj_pdf->SetCreator(PDF_CREATOR);  
                $obj_pdf->SetTitle("RESTAURANT statystyki pracowników");  
                $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
                $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
                $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
                $obj_pdf->SetDefaultMonospacedFont('FreeSans');  
                $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
                $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
                $obj_pdf->setPrintHeader(false);  
                $obj_pdf->setPrintFooter(TRUE);  
                $obj_pdf->SetAutoPageBreak(TRUE, 5);  
                $obj_pdf->SetFont('FreeSans', '', 10);  
                $obj_pdf->AddPage();  
                $content = '';

                $content .= '<h4 color= "red" align="center">raport wygenerowano '.date("Y-m-d_H:i:s").'</h4>';

                $workers = array();//tablica asocjacyjna

                $sqlFirst = "SELECT * FROM users";

                $query = mysqli_query($polaczenie,$sqlFirst);
                
                $summary = 0;
                while($row = mysqli_fetch_assoc($query))
                {
                    $sqlSecond = "SELECT * FROM zamowienia WHERE autor='".$row['login']."'";
                    $ilosc=0;
                    $querySecond = mysqli_query($polaczenie,$sqlSecond);
                    while($rowSecond = mysqli_fetch_assoc($querySecond))
                    {
                        $ilosc++;
                        $summary++;
                    }

                    $workers[''.$row['imie'].' '.$row['nazwisko'].''] = $ilosc;
                }

                arsort($workers); //sortujemy tablice asocjacyjna wdl wartosci malejąco

                //TABELA

                $content .= '<h3>PRACOWNICY</h3><div style="margin: 0 auto;" nobr="true">
                                <table style="text-align: center; margin: 0 auto;" border="1" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <th style="font-weight:bold;" width="10%">Lp</th>  
                                        <th style="font-weight:bold;" width="40%">Pracownik</th>
                                        <th style="font-weight:bold;" width="20%">Zamówienia</th>
                                    </tr>';
                $lp=1;
                foreach ($workers as $klucz => $wartosc)
                {
                    $content .= '<tr>
                                    <td>'.$lp++.'</td>
                                    <td style="text-align:left;">'.$klucz.'</td>
                                    <td>'.$wartosc.'</td>
                                </tr>';
                }

                $content .= '</table></div>';

                $content .= '<h4 color= "red" align="center">Łącznie zamówień: '.$summary.'</h4>';

                $filename = "RESTAURANT-pracownicy-".date("Y-m-d_H:i:s").".pdf";
                $obj_pdf->writeHTML($content);
                ob_end_clean(); // blokujemy wysyłanie danych wyjściowych do przeglądarki
                $obj_pdf->Output($filename, 'I');   
    }

  
?>