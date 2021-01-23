<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	try {
		$name = trim($_POST['name']);

		$code = $conn->prepare("SELECT cd_servico FROM tb_servico WHERE nm_servico = :name");
		$code->bindParam(':name', $name);
		$code->execute();
		$exist = $code->fetch(PDO::FETCH_ASSOC);
		if($exist) {
			$msg = array(
				'msg'  => 'existe',
				'erro' => true
			);
			echo(json_encode($msg));
			exit;
		}

		if(!isset($_POST['id'])) {
			$code = $conn->prepare("INSERT INTO tb_servico(nm_servico) VALUES(:name)");
			$code->bindParam(':name', $name);
			$code->execute();

			$code = $conn->prepare("SELECT cd_servico AS id, nm_servico AS name FROM tb_servico WHERE nm_servico = :name");
			$code->bindParam(':name', $name);
			$code->execute();
			$service = $code->fetch(PDO::FETCH_ASSOC);
			
			$service['erro'] = false;

			echo(json_encode($service));
			exit;
		} else {
			$code = $conn->prepare("UPDATE tb_servico SET nm_servico = :name WHERE cd_servico = :id");
			$code->bindParam(':name',$name);
			$code->bindParam(':id',($_POST['id']));
			$code->execute();

			$service['erro'] = false;
			$service['id'] = $_POST['id'];
			$service['name'] = $name;

			echo(json_encode($service));
			exit;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};