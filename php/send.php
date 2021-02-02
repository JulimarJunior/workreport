<?php
	require_once('connection.php');
	require_once('emailconfig.php');
	$conn = Database::connectionPDO();
     session_start();

	try {
          $date = $_POST['date'];

		if($_SESSION['view'] != false) {
               $code_user = $_SESSION['view'];
          } else {
               $code_user = $_SESSION['user'];
          }

          $send_by = $_SESSION['user'];

          $diasemana = array('Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sábado');

          $code = $conn->prepare("SELECT cd_relatorio FROM tb_relatorio WHERE cd_usuario = :user AND dt_relatorio = :date_report");
		$code->bindParam(':date_report', $date);
		$code->bindParam(':user', $code_user);
		$code->execute();
          $code_report = $code->fetchColumn();

          $code = $conn->prepare("SELECT r.ds_email, r.nm_remetente FROM tb_remetente AS r INNER JOIN tb_remetente_usuario AS ru ON ru.cd_remetente = r.cd_remetente WHERE ru.cd_usuario = :user");
          $code->bindParam(':user', $code_user);
          $code->execute();
          $senders = $code->fetchAll(PDO::FETCH_ASSOC);

          $code = $conn->prepare("SELECT * FROM tb_item_relatorio WHERE cd_relatorio = :report");
          $code->bindParam(':report', $code_report);
          $code->execute();
          $reports = $code->fetchAll(PDO::FETCH_ASSOC);

          $code = $conn->prepare("SELECT u.nm_usuario AS name, u.ds_email AS email, c.nm_cargo AS office FROM tb_usuario AS u JOIN tb_cargo AS c ON c.cd_cargo = u.cd_cargo WHERE cd_usuario = :user");
          $code->bindParam(':user', $code_user);
          $code->execute();
          $user = $code->fetch(PDO::FETCH_ASSOC);

          $code = $conn->prepare("SELECT nm_usuario FROM tb_usuario WHERE cd_usuario = :user");
          $code->bindParam(':user', $send_by);
          $code->execute();
          $by = $code->fetchColumn();

          $emailText = "";
          $i = 0;

          $emailText .= $diasemana[date('w', strtotime($date))] . ' ' . date('d/m/Y', strtotime($date)) . '<br>';
          $emailText .= '<br>';

          foreach($reports AS $report) {
               $emailText .= date('H:i', strtotime($report['hr_inicio'])) .' - '.date('H:i', strtotime($report['hr_final'])).' '. $report['ds_servico'] .'<br>';
               if($report['ds_servico']) {
                    $emailText .= '<b>Descrição: </b>'. $report['ds_descricao'] .'<br>';
               }
               if($report['ds_card']) {
                    $emailText .= '<b>Card: </b><a href="'. $report['ds_card'] .'">'. $report['ds_card'] .'</a><br>';
               }

               $emailText .= '<br>';

               if($i == $_POST['pause']) {
                    $emailText .= '<---------------- Pause ----------------> <br>';
                    $emailText .= '<br>';
               }

               $i++;
          }

          $emailText .= '<hr style="margin-bottom: 25px;">';
          $emailText .= '<b>E-mail enviado por: </b>'. $by;

          $data['subject']    = 'Relatório | '. date('d/m/Y', strtotime($date)) . ' | '. $user['office'] . ' ' . $user['name'];
          $data['name']       = $user['name'];
          $data['email']      = $user['email'];
          $data['body']       = $emailText;
          $data['senders']    = $senders;

          if(Email::enviar($data['body'], $data['name'], $data['email'], $data['subject'], $data['senders'])) {
               echo true;
          }

	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};