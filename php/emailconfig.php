<?php
	class Email {

          public static function enviar( $body, $name, $email, $subject, $senders){
			$return = 0;

			include ('PHPMailer/class.phpmailer.php');
			require 'PHPMailer/PHPMailerAutoload.php';

            $useragent = $_SERVER['HTTP_USER_AGENT'];
               
			// // Inicia a classe PHPMailer
			$mail = new PHPMailer();
			$mail->IsSMTP(); // Define que a mensagem será SMTP
			$mail->Host = ""; // Endereço do servidor SMTP
			$mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)
			$mail->Username = ''; // Usuário do servidor SMTP
			$mail->Password = ''; // Senha do servidor SMTP
			$mail->SMTPSecure = "tls";

			// Define o remetente
			$mail->From = "";
			$mail->FromName = "SummerWorkReport - ". $name; 

			// Define os destinatário(s)
			$mail->AddAddress($email, $name);
			
			foreach($senders as $sender) {
				$mail->AddAddress($sender['ds_email'], $sender['nm_remetente']);
			}

			$mail->AddBCC('', '');

			// Define o retorno de resposta
			$mail->AddReplyTo('', '');

			// Define os dados técnicos da Mensagem
			$mail->IsHTML(true); // Define que o e-mail será enviado como HTML
			$mail->CharSet = 'UTF-8'; // Charset da mensagem (opcional)

			// Define a mensagem (Texto e Assunto)
			$mail->Subject = $subject; // Assunto da mensagem
			$mail->Body = $body; // Corpo da mensagem

			// Envia o e-mail
			$enviado = $mail->Send();

			// Limpa os destinatários e os anexos
			$mail->ClearAllRecipients();
			$mail->ClearAttachments();

			// Exibe uma mensagem de resultado
			if ($enviado) {
			    $return = true;
			}else{
				$return = false;
			}

			return $return;

		}

	}
	
?>