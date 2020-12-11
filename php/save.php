<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	session_start();

	$date = $_POST['date'];
	$cards = $_POST['card'];
	$code_user = $_SESSION['user'];
	$today = date('Y-m-d H:i');
	$pause = $_POST['pause'];

	if($pause == 'null') {
		$pause = NULL;
	}

	try {
		$code = $conn->prepare("SELECT cd_relatorio FROM tb_relatorio WHERE cd_usuario = :user AND dt_relatorio = :date_report");
		$code->bindParam(':date_report', $date);
		$code->bindParam(':user', $code_user);
		$code->execute();
		$exist = $code->fetchColumn();

		if($exist != NULL) {
			$code = $conn->prepare("UPDATE tb_relatorio SET dt_envio = :date_send, qt_pause = :pause WHERE cd_usuario = :user AND dt_relatorio = :date_report");
			$code->bindParam(':date_send', $today);
			$code->bindParam(':date_report', $date);
			$code->bindParam(':user', $code_user);
			$code->bindParam(':pause', $pause);
			$code->execute();

			$code_report = $exist;

			$code = $conn->prepare("DELETE FROM tb_item_relatorio WHERE cd_relatorio = :code_report");
			$code->bindParam(':code_report', $code_report);
			$code->execute();
		} else {
			$code = $conn->prepare("INSERT INTO tb_relatorio(dt_relatorio, dt_envio, cd_usuario, qt_pause) VALUES (:date_report, :date_send, :user, :pause)");
			$code->bindParam(':date_send', $today);
			$code->bindParam(':date_report', $date);
			$code->bindParam(':user', $code_user);
			$code->bindParam(':pause', $pause);
			$code->execute();

			$code = $conn->prepare("SELECT MAX(cd_relatorio) FROM tb_relatorio WHERE cd_usuario = :user AND dt_relatorio = :date_report");
			$code->bindParam(':date_report', $date);
			$code->bindParam(':user', $code_user);
			$code->execute();
			$code_report = $code->fetchColumn();
		}

		$query = "INSERT INTO tb_item_relatorio(hr_inicio, hr_final, ds_servico, ds_card, ds_descricao, cd_relatorio) VALUES ";
		$count = count($cards) - 1;
		$i = 0;
		foreach ($cards as $card) {
			$start_report = $card['start'];
			$end_report = $card['end'];
			$service_report = $card['service'];
			if($card['card'] != NULL) {
				$link_report = $card['card'];				
			} else {
				$link_report = NULL;
			}
			$description_report = $card['description'];
			$query .= "('$start_report', '$end_report', '$service_report', '$link_report', '$description_report', :code_report)";
			if($i < $count) {
				$query .= ",";
			}
			$i ++;
		}
		$code = $conn->prepare($query);
		$code->bindParam(':code_report', $code_report);
		$code->execute();
		
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};