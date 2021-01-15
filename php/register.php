<?php
	require_once('connection.php');
	require_once('functions.php');
	$conn = Database::connectionPDO();

	session_start();
	verifyLogin();

	try {
		$data = array(
			'id' => $_SESSION['user'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],
			'office' => $_POST['office'],
			'adm' => $_POST['adm'],
			'password' => md5($_POST['password']),
			'passwordNew' => md5($_POST['passwordNew']),
			'passwordConfirm' => md5($_POST['passwordConfirm'])
		);

		if($_POST['email'] == '' || $_POST['email'] == null || $_POST['password'] == '' || $_POST['password'] == null || $_POST['name'] == '' || $_POST['name'] == null || $_POST['office'] == '' || $_POST['office'] == null) {
			echo "Informe todos os campos obrigatórios";
			exit;
		}

		if(!mb_strpos($_POST['email'], '@summercomunicacao.com.br')) {
			echo "Informe um e-Mail Summer";
			exit;
		}

		$code = $conn->prepare("SELECT ds_senha FROM tb_usuario WHERE cd_usuario = :user");
		$code->bindParam(':user',($data['id']));
		$code->execute();
		$password = $code->fetchColumn();

		if($password == $data['password']) {
			if($data['passwordNew'] != $data['passwordConfirm']) {
				echo "Novas senhas não coincidem!";
				exit;
			}
			$code = $conn->prepare("INSERT INTO tb_usuario(nm_usuario, cd_cargo, ds_email, ds_senha, ic_administrador) VALUES(:name, :office, :email, :password, :adm);");
			$code->bindParam(':name',($data['name']));
			$code->bindParam(':email',($data['email']));
			$code->bindParam(':password',($data['passwordNew']));
			$code->bindParam(':adm',($data['adm']));
			$code->bindParam(':office',($data['office']));
			$code->execute();

			echo true;
			exit;
		} else {
			echo "Senha atual incorreta!";
			exit;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};