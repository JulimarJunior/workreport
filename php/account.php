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
			'password' => md5($_POST['password']),
			'passwordNew' => md5($_POST['passwordNew']),
			'passwordConfirm' => md5($_POST['passwordConfirm']),
			'file' => $_FILES['image']['name']
		);

		if($_POST['email'] == '' || $_POST['email'] == null || $_POST['password'] == '' || $_POST['password'] == null || $_POST['name'] == '' || $_POST['name'] == null || $_POST['office'] == '' || $_POST['office'] == null) {
			echo "Informe todos os campos obrigatórios";
			exit;
		}

		if(!mb_strpos($_POST['email'], '@summercomunicacao.com.br')) {
			echo "Informe um e-Mail Summer";
			exit;
		}

		$code = $conn->prepare("SELECT ds_senha, ds_imagem FROM tb_usuario WHERE cd_usuario = :user");
		$code->bindParam(':user',($data['id']));
		$code->execute();
		$user_infos = $code->fetch(PDO::FETCH_ASSOC);

		if($user_infos['ds_senha'] == $data['password']) {
			if($data['passwordNew'] != $data['passwordConfirm']) {
				echo "Novas senhas não coincidem!";
				exit;
			}

			if(isset($data['file']) && $data['file'] != '') {
				$url = '../img/user/';
				if(!file_exists($url)) {
					mkdir($url, 0777, true);
				}

				$_UP['folder'] = $url;
	          	$_UP['size'] = 1024 * 1024 * 5; // 5Mb
	          	$_UP['extensions'] = array('jpg', 'jpeg', 'png');

	          	$_UP['error'][0] = 'Não houve erro';
	          	$_UP['error'][1] = 'O arquivo é grande demais';
	          	$_UP['error'][2] = 'O arquivo é grande demais';
	          	$_UP['error'][3] = 'Upload de arquivo realizado parcialmente';
	          	$_UP['error'][4] = 'Upload de arquivo não realizado';

	          	$extension = @strtolower(end(explode('.', $_FILES['image']['name'])));

	          	if (array_search($extension, $_UP['extensions']) === false) {
	          		echo "O tipo de arquivo enviado não é permitido! Tente algo em JPG, JPEG, PNG, PDF ou DOCX.";
	          		exit;
	          	}

	          	if ($_FILES['image']['error'] != 0) {
	          		echo "Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['image']['error']];
	          		exit;
	          	} else if ($_UP['size'] < $_FILES['image']['size']) {
	          		echo "O arquivo enviado é muito grande, envie arquivos de até 5Mb.";
	          		exit;
	          	}

	          	$data['image'] = time().'.'.$extension;

	          	if(!move_uploaded_file($_FILES['image']['tmp_name'], $_UP['folder'] . $data['image'])) {
	          		echo "Não foi possível fazer o upload da imagem, tente novamente";
	          		exit;
	          	}

	          	$_SESSION['image'] = $data['image'];
	      	} else {
	      		$data['image'] = $user_infos['ds_imagem'];
			}
			 
			$code = $conn->prepare("DELETE FROM tb_remetente_usuario WHERE cd_usuario = :user");
			$code->bindParam(':user',($data['id']));
			$code->execute();
			
			foreach($_POST['senders'] as $sender) {
				$code = $conn->prepare("INSERT INTO tb_remetente_usuario(cd_usuario, cd_remetente) VALUES(:user, :sender);");
				$code->bindParam(':user',($data['id']));
				$code->bindParam(':sender', $sender);
				$code->execute();
			}

			$code = $conn->prepare("UPDATE tb_usuario SET nm_usuario = :name, ds_email = :email, cd_cargo = :office, ds_imagem = :image WHERE cd_usuario = :user");
			$code->bindParam(':user',($data['id']));
			$code->bindParam(':name',($data['name']));
			$code->bindParam(':email',($data['email']));
			$code->bindParam(':image',($data['image']));
			$code->bindParam(':office',($data['office']));
			$code->execute();

			$_SESSION['name'] = $data['name'];

			if($_POST['passwordNew']) {
				$code = $conn->prepare("UPDATE tb_usuario SET ds_senha = :password WHERE cd_usuario = :user");
				$code->bindParam(':user',($data['id']));
				$code->bindParam(':password',($data['passwordNew']));
				$code->execute();
			}

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