<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	if($_POST['email'] == '' || $_POST['email'] == null || $_POST['password'] == '' || $_POST['password'] == null) {
		echo "Informe o e-Mail e senha";
		exit;
	}

	if(!mb_strpos($_POST['email'], '@summercomunicacao.com.br')) {
		echo "Informe um e-Mail Summer";
		exit;
	}

	try {
		$code = $conn->prepare("SELECT cd_usuario, nm_usuario, ds_senha, ds_imagem, ic_administrador FROM tb_usuario WHERE ds_email = :email");
		$code->bindParam(':email',($_POST['email']));
		$code->execute();
		$user = $code->fetch(PDO::FETCH_ASSOC);
		$password = $user['ds_senha'];
		if(!$password) {
			echo "e-Mail ou senha incorretos";
			exit;
		} else {
			if(md5($_POST['password']) === $password) {
				session_start();
				$_SESSION['user'] = $user['cd_usuario'];
				$_SESSION['name'] = $user['nm_usuario'];
				$_SESSION['image'] = $user['ds_imagem'];
				$_SESSION['adm'] = $user['ic_administrador'];
				echo true;
				exit;
			} else {
				echo "e-Mail ou senha incorretos";
				exit;
			}
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};