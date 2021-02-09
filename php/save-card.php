<?php
     date_default_timezone_set('America/Sao_Paulo');
	require_once('connection.php');
	$conn = Database::connectionPDO();

     $date = date('Y-m-d H:i:s');

	try {
		$code = $conn->prepare("UPDATE tb_card SET cd_queue = :queueId, cd_ordem = :order, dt_atualizacao = :current WHERE cd_card = :id");
          $code->bindParam(':queueId',($_POST['queueId']));
          $code->bindParam(':order',($_POST['order']));
          $code->bindParam(':current',($date));
          $code->bindParam(':id',($_POST['id']));
          $code->execute();
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};