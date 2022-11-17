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
                $obj_pdf->SetTitle("AGAPE zamówienia");  
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
                    $content .= '<h3 align="center">Zamówienia AGAPE '.$since.'</h3><br /><br />'; 
                }else{
                    $content .= '<h3 align="center">Zamówienia AGAPE od '.$since.' do '.$to.'</h3><br /><br />'; 
                }
                
                $content .= '<h4 color= "red" align="center">raport wygenerowano '.date("Y-m-d_H:i:s").'</h4>';
    
                //wybieramy zamówienie, które nie zostało odebrane jeszcze
                $counter = 0; //licznik numeru zamówienia
                $sql = "SELECT * FROM zamowienia WHERE status_zamowienia = 'nieodebrano' AND  data_odbioru BETWEEN '$since' AND '$to' ORDER BY data_odbioru ASC, nazwisko ASC"; 
                $result = mysqli_query($polaczenie, $sql);
                while($row = mysqli_fetch_array($result)) 
                {
                    $counter++;
                    $content .= '<div nobr="true">
                    <p style="font-weight:bold; font-size: 12px;">'.$counter.'. '.$row["nazwisko"].' '.$row["imie"].', '.$row["telefon"].', '.$row["data_odbioru"].' ';
                    if($row["komentarz"] != NULL)
                    {
                        $content .= ', komentarz: '.$row["komentarz"].'</p>';
                    }else{
                        $content .= '</p>';
                    }
                    //wybieramy produkty do tego zamówienia
                    $sqlSecond = "SELECT * FROM produkty WHERE id_zamowienia = ".$row["id_zamowienia"]."";
                    $suma = 0;
                    $resultSecond = mysqli_query($polaczenie, $sqlSecond);
                    $content .= '<div style="text-align: center;">
                                        <table style="margin: 0 auto;" border="1" cellspacing="0" cellpadding="2">
                                        <tr>
                                            <th style="font-weight:bold;" width="70%">Produkt</th>  
                                            <th style="font-weight:bold;" width="15%">Ilość</th>  
                                            <th style="font-weight:bold;" width="15%">Cena</th>
                                        </tr>            
                            ';
                    while($rowSecond = mysqli_fetch_array($resultSecond))
                    {
                        $suma += $rowSecond["cena"];
                        $content .= '<tr>  
                                            <td style="text-align: left;">'.$rowSecond["produkt"].'</td>  
                                            <td>'.$rowSecond["ilosc"].'</td>  
                                            <td>'.$rowSecond["cena"].'</td>  
                                    </tr>
                                ';
                    }
                    $content .= '<tr style="font-weight: bold;">
                                    <td colspan="2">SUMA</td>
                                    <td>'.$suma.' PLN</td>
                                </tr>
                                </table>
                            </div>
                        </div>';
                }
                $filename = "AGAPE-zamowienia-".$since."-".$to."-".date("Y-m-d_H:i:s").".pdf";
                $obj_pdf->writeHTML($content);  
                $obj_pdf->Output($filename, 'I');   
    }

  
?>