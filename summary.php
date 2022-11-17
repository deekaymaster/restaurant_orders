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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RESTAURANT - zestawienia</title>
    <link rel="icon" type="image/png" href="images/logo.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="css/tabela.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--  extension responsive  -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <!-- extension responsive -->
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

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
        <div class="container">
            <div class="container-fluid table-title mt-3">
                <div class="row d-flex">
                    <div class="col-sm-4">
                        <h2>Lista <b>zamówień</b></h2>
                    </div>
                </div>
            </div>
            <?php
                if($_SESSION['login']=='ojciecdyrektor' || $_SESSION['login']=='admin')
                {
                    echo '<div class="container-fluid mt-3">
                            <form action="exportSummary.php" method="POST">
                                <div class="d-flex align-items-center flex-column flex-md-row justify-content-md-end">
                                    <div class="p-3">
                                        <label class="form-label fw-bold">Od: </label>
                                        <input type="date" name="od" autocomplete="off" required />
                                    </div>
                                    <div class="p-3">
                                        <label class="form-label fw-bold">Do: </label>
                                        <input type="date" name="do" autocomplete="off" required />
                                    </div>
                                    <div class="p-3">
                                        <input type="submit" name="export_excel" class="btn btn-primary" value="Export to PDF" />
                                    </div>
                                </div>
                            </form>
                        </div>';

                    echo '<div class="container-fluid mb-3">
                            <form action="exportWorkers.php" method="POST">
                                <div class="d-flex align-items-center flex-column flex-md-row justify-content-md-end">
                                    <div class="p-3">
                                        <input type="submit" name="export_excel_workers" class="btn btn-primary" value="Statystyki pracowników" />
                                    </div>
                                </div>
                            </form>
                        </div>';
                }else{
                    echo '<div class="container-fluid mb-3 mt-3">
                            <form action="exportSummary.php" method="POST">
                                <div class="d-flex align-items-center flex-column flex-md-row justify-content-md-end">
                                    <div class="p-3">
                                        <label class="form-label fw-bold">Od: </label>
                                        <input type="date" name="od" autocomplete="off" required />
                                    </div>
                                    <div class="p-3">
                                        <label class="form-label fw-bold">Do: </label>
                                        <input type="date" name="do" autocomplete="off" required />
                                    </div>
                                    <div class="p-3">
                                        <input type="submit" name="export_excel" class="btn btn-primary" value="Export to PDF" />
                                    </div>
                                </div>
                            </form>
                        </div>';
                }
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <table id="summaryTable" class="table table-bordered display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nazwa produktu</th>
                                <th>Ilość</th>
                                <th>Cena(suma)</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
<script type="text/javascript">
$(document).ready(function() {

    $('#summaryTable').DataTable({
        'responsive': 'true',
        "fnCreatedRow": function(nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData[0]);
        },
        'serverSide': 'true',
        'processing': 'true',
        'paging': 'true',
        'order': [],
        'ajax': {
            'url': 'ajax/fetchDataSummary.php',
            'type': 'post'
        },
        "columnDefs": [{
            'target': [5],
            'orderable': false,
        }]
    });
});
</script>

</html>