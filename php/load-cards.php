<?php
     date_default_timezone_set('America/Sao_Paulo');
	require_once('connection.php');
	$conn = Database::connectionPDO();

     $date = date('Y-m-d H:i:s');

	try {
          $code = $conn->prepare('SELECT q.cd_queue AS id, q.nm_queue AS title FROM tb_queue AS q WHERE q.ic_ativo = 1 ORDER BY q.cd_ordem');

          $code->execute();
          $queues = $code->fetchAll(PDO::FETCH_ASSOC);

          $i = 0;
          foreach($queues as $queue) {
               if($_POST['reload'] == 'true') {
                    $code = $conn->prepare("SELECT c.cd_card AS 'id', c.nm_card AS 'title', c.ds_card AS 'description', c.cd_ordem AS 'order' FROM tb_card AS c WHERE c.cd_queue = :queue AND c.ic_arquivado = 0 AND c.ic_ativo = 1 AND c.dt_atualizacao >= :last ORDER BY c.cd_ordem");
                    $code->bindParam(':last',($_POST['lastUpdate']));
               } else {
                    $code = $conn->prepare("SELECT c.cd_card AS 'id', c.nm_card AS 'title', c.ds_card AS 'description', c.cd_ordem AS 'order' FROM tb_card AS c WHERE c.cd_queue = :queue AND ic_arquivado = 0 AND ic_ativo = 1 ORDER BY c.cd_ordem");
               }
               $code->bindParam(':queue',$queue['id']);
               $code->execute();
               $cards = $code->fetchAll(PDO::FETCH_ASSOC);
               
               $cardsTemp = array();
               $ii = 0;
               foreach($cards as $card) {
                    $cardsTemp[$card['id']] = $ii;
                    $ii++;
               }

               $queues[$i]['cards'] = $cards;
               
               foreach($cards as $card) {
                    $code = $conn->prepare("SELECT u.nm_usuario AS 'name', u.ds_email AS 'email', u.ds_imagem AS 'image' FROM tb_usuario AS u INNER JOIN tb_card_usuario AS uc ON u.cd_usuario = uc.cd_usuario WHERE uc.cd_card = :card");
                    $code->bindParam(':card',($card['id']));
                    $code->execute();
                    $members = $code->fetchAll(PDO::FETCH_ASSOC);

                    $queues[$i]['cards'][$cardsTemp[$card['id']]]['members'] = $members;
               }


               $i++;
          }

          $queues['update'] = $date;
          
          echo json_encode($queues);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};