<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	session_start();

	$text = $_POST['text'];
	$text = str_replace('nArray','&',$text);

	$date = str_replace('/','-',$_POST['date']);
	$date = explode('-',$date);

	$date = $date[2].'-'.$date[1].'-'.$date[0];

	$today = date('Y-m-d H:i');
	$code_user = $_SESSION['code'];

	try {
		$code = $conn->prepare("SELECT cd_relatorio FROM tb_relatorio WHERE cd_usuario = :user AND dt_relatorio = :date_report");
		$code->bindParam(':date_report', $date);
		$code->bindParam(':user', $code_user);
		$code->execute();
		$exist = $code->fetchColumn();

		if($exist != NULL) {
			$code = $conn->prepare("UPDATE tb_relatorio SET ds_relatorio = :report, dt_envio = :date_send WHERE cd_usuario = :user AND dt_relatorio = :date_report");
		} else {
			$code = $conn->prepare("INSERT INTO tb_relatorio(ds_relatorio,dt_relatorio,dt_envio,cd_usuario) VALUES (:report, :date_report, :date_send, :user)");
		}
		$code->bindParam(':report', $text);
		$code->bindParam(':date_report', $date);
		$code->bindParam(':date_send', $today);
		$code->bindParam(':user', $code_user);
		$code->execute();

		
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}