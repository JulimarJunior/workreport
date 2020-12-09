<?php
	require_once('connection.php');
	$conn = Database::connectionPDO();

	$date = date('d/m/Y', strtotime($_POST['date']));

	session_start();
	$_SESSION['code'] = 1;
	$code_user = $_SESSION['code'];

	try {
		$code = $conn->prepare("SELECT u.nm_usuario AS name, c.nm_cargo AS role FROM tb_usuario AS u JOIN tb_cargo AS c ON c.cd_cargo = u.cd_cargo WHERE cd_usuario = :code");
		$code->bindParam(':code',$code_user);
		$code->execute();
		$user = $code->fetch(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}

?>

	<b>Relatório | <span class="date_report"><?= $date ?></span> | <?= $user['role'] ?> <?= $user['name'] ?></b>
	<div class="mt-3 report">
		<?php
		$cards = $_POST['card'];
		foreach ($cards as $card) {
			if($card['start'] != NULL) {
				?>
				<?= $card['start'] ?>&ensp;&ensp;-&ensp;&ensp;<?= $card['end'] ?> <b><?= $card['service'] ?></b>
				<br>
				<b>Descrição:</b> <?= $card['description'] ?>
				<?php
				if($card['card'] != NULL) {
					?>
					<br>
					<b>Card:</b> <a href="<?= $card['card'] ?>"><?= $card['card'] ?></a>
					<?php
				}
				?>
				<br>
				<br>
				<?php
			}
			if($card['order'] == $_POST['pause']) {
				?>
				<----------------Pause---------------->
				<br>
				<br>
				<?php
			}
		}
		?>
	</div>
