<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	try {
		if($_POST['type'] == 'user') {
			$code = $conn->prepare("DELETE FROM tb_remetente_usuario WHERE cd_usuario = :user");
			$code->bindParam(':user',($_POST['id']));
			$code->execute();

			$code = $conn->prepare("SELECT cd_relatorio FROM tb_relatorio WHERE cd_relatorio = :user");
			$code->bindParam(':user',($_POST['id']));
			$code->execute();
			$reports = $code->fetchAll(PDO::FETCH_ASSOC);

			if(count($reports) > 0) {
				$sql = "DELETE FROM tb_item_relatorio WHERE ";
				$max = count($reports);
				$i = 1;
				foreach ($reports as $report) {
					if($i == $max) {
						$sql .= "cd_relatorio = ".$report['cd_relatorio'].";";
					} else {
						$sql .= "cd_relatorio = ".$report['cd_relatorio']." OR ";
					}
					$i ++;
				}
				$code = $conn->prepare($sql);
				$code->execute();

				$code = $conn->prepare("DELETE FROM tb_relatorio WHERE cd_usuario = :user");
				$code->bindParam(':user',($_POST['id']));
				$code->execute();
			}

			$code = $conn->prepare("DELETE FROM tb_usuario WHERE cd_usuario = :user");
			$code->bindParam(':user',($_POST['id']));
			$code->execute();

			echo true;
			exit;
		}

		if($_POST['type'] == 'office') {
			$code = $conn->prepare("SELECT COUNT(cd_usuario) FROM tb_usuario WHERE cd_cargo = :office");
			$code->bindParam(':office',($_POST['id']));
			$code->execute();
			$users = $code->fetchColumn();

			if($users != 0) {
				echo 'Não é possível remover o cargo pois existem usuários atrelados a ele!';
				exit;
			}

			$code = $conn->prepare("DELETE FROM tb_cargo WHERE cd_cargo = :office");
			$code->bindParam(':office',($_POST['id']));
			$code->execute();

			echo true;
		}

		if($_POST['type'] == 'service') {
			$code = $conn->prepare("DELETE FROM tb_servico WHERE cd_servico = :service");
			$code->bindParam(':service',($_POST['id']));
			$code->execute();

			echo true;
		}

		if($_POST['type'] == 'sender') {
			$code = $conn->prepare("DELETE FROM tb_remetente_usuario WHERE cd_remetente = :sender");
			$code->bindParam(':sender',($_POST['id']));
			$code->execute();

			$code = $conn->prepare("DELETE FROM tb_remetente WHERE cd_remetente = :sender");
			$code->bindParam(':sender',($_POST['id']));
			$code->execute();

			echo true;
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};