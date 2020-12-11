<?php
    session_start();
    $name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0'">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">

    <!-- Fontes -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800,900&display=swap" rel="stylesheet">
    
    <script src="https://kit.fontawesome.com/aa4b28b8ec.js" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Relatórios Summer</title>

</head>
<body>
    <div class="navbar-group">
        <div class="container">
            <div class="row">
                <div class="col-6 pl-0 my-auto">
                    <ul class="left">
                        <li><a href="list.php">Meus relatórios</a></li>
                        <li><a href="">Minha conta</a></li>
                    </ul>
                </div>
                <div class="col-6 pr-0 text-right my-auto">
                     <ul class="right">
                        <li><span>Logado como <b><?= $name ?></b></span></li>
                        <li><a href="php/logout.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>