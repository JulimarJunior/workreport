<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	try {
		$name = trim($_POST['name']);

		$code = $conn->prepare("SELECT cd_cargo FROM tb_cargo WHERE nm_cargo = :name");
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
			$code = $conn->prepare("INSERT INTO tb_cargo(nm_cargo) VALUES(:name)");
			$code->bindParam(':name', $name);
			$code->execute();

			$code = $conn->prepare("SELECT cd_cargo AS id, nm_cargo AS name FROM tb_cargo WHERE nm_cargo = :name");
			$code->bindParam(':name', $name);
			$code->execute();
			$office = $code->fetch(PDO::FETCH_ASSOC);
			
			$office['erro'] = false;

			echo(json_encode($office));
			exit;
		} else {
			$code = $conn->prepare("UPDATE tb_cargo SET nm_cargo = :name WHERE cd_cargo = :id");
			$code->bindParam(':name',$name);
			$code->bindParam(':id',($_POST['id']));
			$code->execute();

			$office['erro'] = false;
			$office['id'] = $_POST['id'];
			$office['name'] = $name;

			echo(json_encode($office));
			exit;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};