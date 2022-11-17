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
                $since = $_POST['od'];
                $to = $_POST['do'];

                $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
                $obj_pdf->SetCreator(PDF_CREATOR);  
                $obj_pdf->SetTitle("AGAPE zestawienia");  
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
                if($since == $to)
                {
                    $content .= '<h3 align="center">Zestawienia AGAPE '.$since.'</h3><br /><br />'; 
                }else{
                    $content .= '<h3 align="center">Zestawienia AGAPE od '.$since.' do '.$to.'</h3><br /><br />'; 
                }
    
                $content .= '<h4 color= "red" align="center">raport wygenerowano '.date("Y-m-d_H:i:s").'</h4>';

                //KUCHNIA
                $content .= '<h3>KUCHNIA</h3><div nobr="true">
                                <table style="text-align: center; margin: 0 auto;" border="1" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <th style="font-weight:bold;" width="70%">Produkt</th>  
                                        <th style="font-weight:bold;" width="15%">Ilość</th>  
                                        <th style="font-weight:bold;" width="15%">Cena</th>
                                    </tr>';
                
                $sqlFirst = "SELECT * FROM lista_produktow WHERE pracownia='kuchnia' ORDER BY nazwa_produktu ASC";

                $query = mysqli_query($polaczenie,$sqlFirst);

                while($row = mysqli_fetch_assoc($query))
                {
                    $sqlSecond = "SELECT * FROM produkty WHERE produkt='".$row['nazwa_produktu']."'";
                    $ilosc=0;
                    $cena=0;
                    $querySecond = mysqli_query($polaczenie,$sqlSecond);
                    while($rowSecond = mysqli_fetch_assoc($querySecond))
                    {
                        //SPRAWDZAMY CZY TAKIE ZAMÓWIENIE JEST W TYM ZAKRESIE DAT
                        $sqlThird = "SELECT * FROM zamowienia WHERE status_zamowienia = 'nieodebrano' AND id_zamowienia='".$rowSecond['id_zamowienia']."' AND data_odbioru BETWEEN '$since' AND '$to'";
                        $queryThird = mysqli_query($polaczenie,$sqlThird);
                        $count_rows = mysqli_num_rows($queryThird);//ile produktów spełnia powyższe zapytanie
                        if($count_rows == 0); //jeśli żaden
                        else
                        {
                            $ilosc = $ilosc + $rowSecond['ilosc'];
                            $cena = $cena + $rowSecond['cena'];
                        }
                    }

                    if($ilosc != 0)
                    {
                        $content .= '<tr>
                                        <td style="text-align:left;">'.$row['nazwa_produktu'].'</td>
                                        <td>'.$ilosc.'</td>
                                        <td>'.$cena.'</td>
                                    </tr>';
                    }
                }

                $content .= '</table></div>';

                //CUKIERNIA

                $content .= '<h3>CUKIERNIA</h3><div nobr="true">
                                <table style="text-align: center; margin: 0 auto;" border="1" cellspacing="0" cellpadding="2">
                                    <tr>
                                        <th style="font-weight:bold;" width="70%">Produkt</th>  
                                        <th style="font-weight:bold;" width="15%">Ilość</th>  
                                        <th style="font-weight:bold;" width="15%">Cena</th>
                                    </tr>';
                
                $sqlFirst = "SELECT * FROM lista_produktow WHERE pracownia='cukiernia' ORDER BY nazwa_produktu ASC";

                $query = mysqli_query($polaczenie,$sqlFirst);

                while($row = mysqli_fetch_assoc($query))
                {
                    $sqlSecond = "SELECT * FROM produkty WHERE produkt='".$row['nazwa_produktu']."'";
                    $ilosc=0;
                    $cena=0;
                    $querySecond = mysqli_query($polaczenie,$sqlSecond);
                    while($rowSecond = mysqli_fetch_assoc($querySecond))
                    {
                        //SPRAWDZAMY CZY TAKIE ZAMÓWIENIE JEST W TYM ZAKRESIE DAT
                        $sqlThird = "SELECT * FROM zamowienia WHERE status_zamowienia = 'nieodebrano' AND id_zamowienia='".$rowSecond['id_zamowienia']."' AND data_odbioru BETWEEN '$since' AND '$to'";
                        $queryThird = mysqli_query($polaczenie,$sqlThird);
                        $count_rows = mysqli_num_rows($queryThird);//ile produktów spełnia powyższe zapytanie
                        if($count_rows == 0); //jeśli żaden
                        else
                        {
                            $ilosc = $ilosc + $rowSecond['ilosc'];
                            $cena = $cena + $rowSecond['cena'];
                        }
                    }

                    if($ilosc != 0)
                    {
                        $content .= '<tr>
                                        <td style="text-align:left;">'.$row['nazwa_produktu'].'</td>
                                        <td>'.$ilosc.'</td>
                                        <td>'.$cena.'</td>
                                    </tr>';
                    }
                }

                $content .= '</table></div>';

                $filename = "AGAPE-zestawienia-".$since."-".$to."-".date("Y-m-d_H:i:s").".pdf";
                $obj_pdf->writeHTML($content);  
                $obj_pdf->Output($filename, 'I');   
    }

  
?>