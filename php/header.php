<?php
    session_start();
    date_default_timezone_set('America/Sao_Paulo');
    if(isset($_SESSION['name'])) {
        $name = $_SESSION['name'];
        $image = $_SESSION['image'];
        $adm = $_SESSION['adm'];
    }
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
    <link rel="stylesheet" type="text/css" href="css/lmdd.css">
    <title>SummerWorkReport</title>

</head>
<body>
    <div class="modal-fullpage" id="alert-msg">
        <div class="container">
            <div class="row" style="width: 100%; height: 100vh">
                <div class="mx-auto my-auto">
                    <div class="modal-square">
                        <div class="row modal-content-header" style="margin-left: 0px;">
                            <div class="col-10">
                                <h1>Título</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            <p style="margin: 0">Texto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
        if(isset($_SESSION['name'])) {
        ?>
        <div class="navbar-group">
            <div class="container">
                <div class="row">
                    <div class="col-6 pl-0 my-auto">
                        <button class="btn btn-color01 btn-menu" onclick="troggleMenu()"><span class="hamburguer"></span></button>
                        <ul class="left">
                            <li><a href="list"><img src="img/logo.png" alt="Summer Comunicação" title="Summer Comunicação" style="width: 75px"></a></li>
                            <li><a href="list">Meus relatórios</a></li>
                            <li><a href="account">Minha conta</a></li>
                            <li><a href="cards">Cartões</a></li>
                            <?php 
                                if(isset($adm) && $adm == 1) {
                                    ?><li><a href="admin">Administrar</a></li><?php
                                }
                            ?>
                        </ul>
                    </div>
                    <div class="col-md-6 pr-0 text-right my-auto">
                        <ul class="right">
                            <li class="name-user"><a href="account" style="color: inherit;"><span><b><?= mb_strimwidth($name, 0, 20, "."); ?></b></span></a></li>
                            <li><a href="account"><img src="img/user/<?= $image ?>" class="image-user-navbar"></a></li>
                            <li><a href="php/logout"><i class="fas fa-sign-out-alt"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
    ?>