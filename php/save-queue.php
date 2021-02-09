<?php
     date_default_timezone_set('America/Sao_Paulo');
	require_once('connection.php');
	$conn = Database::connectionPDO();

     $date = date('Y-m-d H:i:s');

	try {
		$code = $conn->prepare("UPDATE tb_queue SET nm_queue = :title, dt_atualizacao = :current WHERE cd_queue = :id");
          $code->bindParam(':title',($_POST['name']));
          $code->bindParam(':current',($date));
          $code->bindParam(':id',($_POST['id']));
          $code->execute();
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};