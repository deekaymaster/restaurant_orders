<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/logo.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script
			  src="https://code.jquery.com/jquery-3.6.1.js"
			  integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
			  crossorigin="anonymous"></script>
    <title>RESTAURANT - panel zamówień</title>
    <style>
    body {
        background-color: #F2F2F2;
    }

    nav {
        border-bottom: 1px solid #F22248;
    }

    #btnLogOut {
        background-color: #F22248;
        border: #D91E2E;
    }

    li a {
        border-bottom: 2px solid transparent;
    }

    li a:hover {
        border-bottom: 2px solid #F22248;
    }

    #removeRow {
        width: 100%;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
    </style>
</head>

<body>
    <header class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="orderPanel.php"><img src="images/logo.png" height="48"
                        class="d-inline-block align-text-top" alt="logo" /></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="orderPanel.php">Panel zamówień</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="orders.php">Zamówienia</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="summary.php">Zestawienia</a>
                        </li>
                    </ul>
                    <div class="h-100" style="padding-right:5px; color:#664B39;">Jesteś zalogowany jako <b><span
                                style="color:#F22248;">
                                <?php
                        echo $_SESSION['login'];
                    ?>
                            </span></b></div>
                    <form class="d-flex" action="logout.php">
                        <button id="btnLogOut" class="btn btn-md btn-primary btn-block" type="submit">Wyloguj</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <main class="mb-5">
        <div class="container" style="max-width:700px;">

            <div class="text-center" style="margin: 20px 0px 20px 0px;">
                <span class="text-secondary">Dodaj zamówienie</span>
            </div>

            <form action="saveOrder.php" method="POST">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="inputFormName">
                            <div class="input-group mb-3">
                                <input type="text" name="imie" class="form-control m-input" placeholder="Wprowadź imie"
                                    autocomplete="off" required>
                            </div>
                        </div>
                        <div id="inputFormSurname">
                            <div class="input-group mb-3">
                                <input type="text" name="nazwisko" class="form-control m-input"
                                    placeholder="Wprowadź nazwisko" autocomplete="off" required>
                            </div>
                        </div>
                        <div id="inputFormPhone">
                            <div class="input-group mb-3">
                                <input type="tel" id="phone" name="phone" placeholder="Wprowadź numer telefonu"
                                    class="form-control m-input" autocomplete="off" required>
                            </div>
                        </div>
                        <div id="inputFormDate">
                            <div class="input-group mb-3">
                                <input type="date" name="odbior" class="form-control m-input" autocomplete="off"
                                    required>
                            </div>
                        </div>
                        <div id="inputFormRow" class="input_fields_container">
                            <div class="input-group mb-3 d-flex justify-content-between">
                                <label class="form-label text-center fw-bold col-5">Produkt</label>
                                <label class="form-label text-center fw-bold col-2">Ilość</label>
                                <label class="form-label text-center fw-bold col-2">Cena</label>
                                <label class="form-label col-2"></label>
                            </div>
                            <div class="input-group mb-3 d-flex justify-content-between">
                                <!--
                                TWORZYMY SELECTA(pobierając opcje do niego z bazy danych)
                                -->
                                <?php
                                    echo '<select name="product[0][name]" class="col-5 selectProductName" required>';
                                    echo '<option value="">--Wybierz zestaw--</option>';

                                    require_once "connect.php";

                                    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
                                    
                                    if ($polaczenie->connect_errno!=0)
                                    {
                                        echo "Error: ".$polaczenie->connect_errno;
                                    }
                                    else
                                    {

                                        $wynik = mysqli_query($polaczenie,"SELECT * FROM lista_produktow ORDER BY nazwa_produktu ASC");

                                        while($row = mysqli_fetch_array($wynik))
                                            {
                                                echo '<option name="'.$row['id_produktu'].'" value="'.$row['nazwa_produktu'].'">'.$row['nazwa_produktu'].'</option>';
                                            }
                                            echo '</select>';

                                        $polaczenie->close();
                                    }
                                ?>

                                <input name="product[0][quantity]" type="number" class="col-2 quantityClass text-center"
                                    min="0" step="0.001" autocomplete="off" readonly />

                                <input name="product[0][price]" type="number" class="col-2 priceClass text-center"
                                    autocomplete="off" readonly />

                                <div class="input-group-append col-2 d-flex justify-content-end">
                                    <button id="addRow" type="button" class="btn btn-info add_more_button"
                                        style="width:100%;">+</button>
                                </div>
                                <input type="hidden" id="lastRowId" value="0" />
                            </div>
                        </div>
                    </div>
                    <!--SUMA ZAMÓWIENIA-->
                    <div id="sumRow" class="input-group-append col-12 d-flex justify-content-center mb-3">
                        <div id="pSum" class="col-auto fw-bold text-center" style="font-size:20px;">
                            SUMA ZAMÓWIENIA:
                        </div>
                        <input name="suma" id="suma" type="hidden" value="" readonly />
                    </div>
                    <div id="formTextArea">
                        <textarea name="comment" class="form-control mb-3"
                            placeholder="Dodaj komentarz do zamówienia"></textarea>
                    </div>
                    <div id="inputOrder" class="d-flex justify-content-center">
                        <input type="submit" class="btn btn-lg btn-primary btn-block col-12" value="Złóż zamówienie">
                    </div>
                </div>
            </form>
        </div>
        <script>
        $(document).ready(function() {
            //CZYŚCIMY WARTOŚĆ SELECTA NA WYPADEK COFNIECIA STRONY WSTECZ PO ZŁOŻENIU ZAMÓWIENIA   
            document.getElementsByClassName("selectProductName")[0].selectedIndex = "0";
            var sumInterval
            var priceInterval
            //FUNKCJA ZAOKRĄGLAJĄCA DO scale MIEJSC PO PRZECINKU
            function roundNumber(num, scale) {
                if (!("" + num).includes("e")) {
                    return +(Math.round(num + "e+" + scale) + "e-" + scale);
                } else {
                    var arr = ("" + num).split("e");
                    var sig = ""
                    if (+arr[1] + scale > 0) {
                        sig = "+";
                    }
                    return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
                }
            }

            //Po wybraniu produktu selectem
            $(document).on('change', '.selectProductName', function() {
                console.log("val", $(this).val());
                console.log("name ", $(this).find('option:selected').attr("name"));
                var productId = $(this).find('option:selected').attr(
                    "name"
                ); //id wybranego produktu(jest to id w bazie danych, w tabeli lista_produktow!!!), pobrane z atrybutu name wybranego <option>
                //<input quantity>
                $(this).nextAll('input').first().removeAttr(
                    "readonly"); //usuwamy dla <input quantity> attr. readonly
                $(this).nextAll('input').first().attr('required',
                    'required'); //ustawiamy dla <input quantity> attr. required
                $(this).nextAll('input').first()
                    .removeClass(); //usuwamy dla <input quantity> wszystkie klasy
                $(this).nextAll('input').first().addClass(
                    productId); //dodajemy dla <input quantity> klase z id obecnie wybranego produktu
                $(this).nextAll('input').first().addClass(
                    "text-center"); //dodajemy dla <input quantity> klase centrującą text
                $(this).nextAll('input').first().val(''); //czyścimy value dla <input quantity>
                $(this).nextAll('input').first().addClass(
                    'col-2'); //dodajemy dla <input quantity> klase col-2
                $(this).nextAll('input').first().addClass(
                    'quantityClass'); //dodajemy dla <input quantity> klase quantityClass
                //<input price>
                var parent = $(this).parent();
                parent.children(':nth-child(3)')
                    .removeClass(); //usuwamy dla <input price> wszystkie klasy
                parent.children(':nth-child(3)').addClass(
                    productId); //dodajemy dla <input price> klase z id obecnie wybranego produktu
                parent.children(':nth-child(3)').addClass(
                    "text-center"); //dodajemy dla <input price> klase centrującą text
                parent.children(':nth-child(3)').addClass(
                    'col-2'); //dodajemy dla <input price> klase col-2
                parent.children(':nth-child(3)').addClass(
                    'priceClass'); //dodajemy dla <input price> klase priceClass
                parent.children(':nth-child(3)').val('') //czyścimy value dla <input price>
                //suma
                $('suma').val("") //czyścimy zawartość inputa przechowującego wartość sumy
                $('#pSum').text('SUMA ZAMÓWIENIA: '); // czyścimy wyświetlaną sume
            })

            //Wyliczenie ceny po wpisaniu wagi/ilości
            $(document).on('focus', '.quantityClass', function() {
                var elem = $(this)
                var idProd = elem.prevAll().first().find('option:selected').attr(
                    "name"
                ); //id_produktu(id w bazie danych!!!) który jest obecnie wybrany, pobrane z atrybutu name wybranego <option>
                clearInterval(sumInterval); //zatrzymujemy interwał, by móc uruchomić kolejny
                priceInterval = setInterval(function() {
                    $.ajax({
                        type: 'POST', //typ połączenia, domyślnie get
                        url: 'ajax/getPrice.php', //gdzie się łączymy
                        data: 'id_produktu=' + idProd, //dane do wysyłki
                        dataType: 'json',
                        success: function(value) {
                            var wynik = ile * value.price //mnożymy ilość przez cene
                            wynik = roundNumber(wynik,
                                2) //zaokrąglamyy do 2 miejsc po przecinku
                            elem.nextAll('input').first().val(
                                wynik) //wpisanie wartości w <input price>
                        }
                    });
                    var ile = parseFloat(elem.val()) //wartość wpisana w <input quantity>
                    //Liczymy sume należności za zamówienie
                    var len = document.getElementsByClassName("priceClass")
                        .length //ile jest inputów z ceną
                    var sum = 0
                    for (var i = 0; i < len; i++) {
                        var cena = parseFloat(document.getElementsByClassName("priceClass")[i]
                            .value)
                        sum += cena
                        sum = roundNumber(sum,
                            2) //zaokrąglamyy do 2 miejsc po przecinku
                    }
                    $("#suma").val(sum);
                    if (isNaN(sum)) $('#pSum').text('SUMA ZAMÓWIENIA: '); // wyświetlamy sume
                    else $('#pSum').text('SUMA ZAMÓWIENIA: ' + sum +
                        ' PLN'); // wyświetlamy sume
                }, 100);

            })
            //Zatrzymanie interwału liczącego, po wyjściu z <input quantity>
            $(document).on('focusout', '.quantityClass', function() {
                clearInterval(priceInterval); //zatrzymujemy interwał, by móc uruchomić kolejny

                //Liczymy sume należności za zamówienie
                sumInterval = setInterval(function() {
                    var len = document.getElementsByClassName("priceClass")
                        .length //ile jest inputów z ceną
                    var sum = 0
                    for (var i = 0; i < len; i++) {
                        var cena = parseFloat(document.getElementsByClassName("priceClass")[i]
                            .value)
                        sum += cena
                        sum = roundNumber(sum,
                            2) //zaokrąglamyy do 2 miejsc po przecinku
                    }
                    $("#suma").val(sum);
                    if (isNaN(sum)) $('#pSum').text('SUMA ZAMÓWIENIA: '); // wyświetlamy sume
                    else $('#pSum').text('SUMA ZAMÓWIENIA: ' + sum +
                        ' PLN'); // wyświetlamy sume
                }, 100);
            })



            //Po kliknięciu +
            $('.add_more_button').click(function(
                e) {
                var x = $('#lastRowId').val(); //id ostatniego wiersza 
                console.log("id poprzedniego wiersza: " + x);
                e.preventDefault();
                x++;
                $.ajax({
                    type: 'POST', //typ połączenia, domyślnie get
                    url: 'ajax/addRow.php', //gdzie się łączymy
                    data: 'x=' + x, //dane do wysyłki, tj. id dodawanego wiersza
                    success: function(html) { //po otrzymaniu odpowiedzi
                        $('#inputFormRow').append(html);
                    }
                });
                $('#lastRowId').val(x); //ustawiamy wartość id ostatniego dodanego wiersza na nową
            });


            //Po kliknięciu usuń
            $('.input_fields_container').on("click", ".remove_field", function(
                e) { //user click on remove text links
                e.preventDefault();
                $(this).parents(':nth(1)').remove()

                //Liczymy sume należności za zamówienie
                sumInterval = setInterval(function() {
                    var len = document.getElementsByClassName("priceClass")
                        .length //ile jest inputów z ceną
                    var sum = 0
                    for (var i = 0; i < len; i++) {
                        var cena = parseFloat(document.getElementsByClassName("priceClass")[i]
                            .value)
                        sum += cena
                        sum = roundNumber(sum,
                            2) //zaokrąglamyy do 2 miejsc po przecinku
                    }
                    $("#suma").val(sum);
                    if (isNaN(sum)) $('#pSum').text('SUMA ZAMÓWIENIA: '); // wyświetlamy sume
                    else $('#pSum').text('SUMA ZAMÓWIENIA: ' + sum +
                        ' PLN'); // wyświetlamy sume
                }, 100);
            })

            //WYŁĄCZENIE SUBMITOWANIA ENTEREM
            $("form").on("keypress", function(event) {
                console.log("aaya");
                var keyPressed = event.keyCode || event.which;
                if (keyPressed === 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });
        </script>
    </main>
</body>

</html>