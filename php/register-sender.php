<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	try {
          if(!mb_strpos($_POST['email'], '@summercomunicacao.com.br')) {
			$msg = array(
				'msg'  => 'email_summer',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
			exit;
          }
          
		$name = trim($_POST['name']);
          $email = trim($_POST['email']);
          

		$code = $conn->prepare("SELECT cd_remetente FROM tb_remetente WHERE ds_email = :email");
		$code->bindParam(':email', $email);
		$code->execute();
		$exist = $code->fetch(PDO::FETCH_ASSOC);
		if($exist && !isset($_POST['id'])) {
			$msg = array(
				'msg'  => 'existe',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}

		if(!isset($_POST['id'])) {
			$code = $conn->prepare("INSERT INTO tb_remetente(nm_remetente, ds_email) VALUES(:name, :email)");
			$code->bindParam(':name', $name);
			$code->bindParam(':email', $email);
			$code->execute();

			$code = $conn->prepare("SELECT cd_remetente AS id, nm_remetente AS name, ds_email AS email FROM tb_remetente WHERE ds_email = :email");
			$code->bindParam(':email', $email);
			$code->execute();
			$sender = $code->fetch(PDO::FETCH_ASSOC);
			
			$sender['erro'] = false;

			echo(json_encode($sender));
			exit;
		} else {
			$code = $conn->prepare("UPDATE tb_remetente SET nm_remetente = :name, ds_email = :email WHERE cd_remetente = :id");
			$code->bindParam(':name',$name);
			$code->bindParam(':email',$email);
			$code->bindParam(':id',($_POST['id']));
			$code->execute();

			$sender['erro'] = false;
			$sender['id'] = $_POST['id'];
			$sender['name'] = $name;
			$sender['email'] = $email;

			echo(json_encode($sender));
			exit;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};