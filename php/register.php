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
			$msg = array(
				'msg'  => 'campos',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}

		if(!mb_strpos($_POST['email'], '@summercomunicacao.com.br')) {
			$msg = array(
				'msg'  => 'email_summer',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}

		$code = $conn->prepare("SELECT cd_usuario FROM tb_usuario WHERE ds_email = :email");
		$code->bindParam(':email',($data['email']));
		$code->execute();
		$email_exist = $code->fetchColumn();
		if($email_exist) {
			$msg = array(
				'msg'  => 'email_cadastrado',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}

		$code = $conn->prepare("SELECT ds_senha FROM tb_usuario WHERE cd_usuario = :user");
		$code->bindParam(':user',($data['id']));
		$code->execute();
		$password = $code->fetchColumn();

		if($password == $data['password']) {
			if($data['passwordNew'] != $data['passwordConfirm']) {
				$msg = array(
					'msg'  => 'senhas_nao_coincidem',
					'erro' => true
				);
				echo(json_encode($msg));
				exit;
			}
			$code = $conn->prepare("INSERT INTO tb_usuario(nm_usuario, cd_cargo, ds_email, ds_senha, ic_administrador) VALUES(:name, :office, :email, :password, :adm);");
			$code->bindParam(':name',($data['name']));
			$code->bindParam(':email',($data['email']));
			$code->bindParam(':password',($data['passwordNew']));
			$code->bindParam(':adm',($data['adm']));
			$code->bindParam(':office',($data['office']));
			$code->execute();

			$code = $conn->prepare("SELECT cd_usuario AS id, nm_usuario AS name, ds_email AS email, cd_cargo AS office FROM tb_usuario WHERE ds_email = :email");
			$code->bindParam(':email',($data['email']));
			$code->execute();
			$user = $code->fetch(PDO::FETCH_ASSOC);

			foreach($_POST['senders'] as $sender) {
				$code = $conn->prepare("INSERT INTO tb_remetente_usuario(cd_usuario, cd_remetente) VALUES(:user, :sender);");
				$code->bindParam(':user', ($user['id']));
				$code->bindParam(':sender', $sender);
				$code->execute();
			}

			$user['erro'] = false;

			echo(json_encode($user));
			exit;
		} else {
			$msg = array(
				'msg'  => 'senha_incorreta',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};