<?php
	require_once('connection.php');
	require_once('functions.php');
	date_default_timezone_set('America/Sao_Paulo');

	session_start();
	verifyLogin();

	$conn = Database::connectionPDO();

	$date = date('d/m/Y', strtotime($_POST['date']));

	if($_SESSION['view'] != false) {
		$code_user = $_SESSION['view'];
	} else {
		$code_user = $_SESSION['user'];
	}
	

	try {
		$code = $conn->prepare("SELECT u.nm_usuario AS name, c.nm_cargo AS role, u.ds_imagem AS image FROM tb_usuario AS u JOIN tb_cargo AS c ON c.cd_cargo = u.cd_cargo WHERE cd_usuario = :code");
		$code->bindParam(':code',$code_user);
		$code->execute();
		$user = $code->fetch(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}

?>

	<div class="card-group card-user row">
		<div class="col-md-1 pr-0">
			<div class="image-user" style="background-image: url('img/user/<?= $user['image'] ?>');">
				
			</div>
		</div>
		<div class="col-md-7">
			<b><?= $user['name'] ?></b>
			<br>
			<?= $user['role'] ?> 
		</div>
		<!-- <div class="col-md-4 text-right">
			<span class="date_report"><?= $date ?></span>
		</div> -->
	</div>
	<div class="mt-3 report">
		<?php
		$cards = $_POST['card'];
		foreach ($cards as $card) {
			if($card['start'] != NULL) {
				?>
				<div class="card-group row">
					<div class="col-12 col-md-5 mb-2">
						<b>Horário: </b><?= date('H:i', strtotime($card['start'])) ?>hrs às <?= date('H:i', strtotime($card['end'])) ?>hrs
					</div>
					<div class="col-12 col-md-7 mb-2">
						<b><?= $card['service'] ?></b>
					</div>
					<div class="col-12 col-md-8">
						<?php
							if($card['description']) {
							?>
							<b>Descrição: </b><?= $card['description'] ?>
							<?php
							}
						?>
					</div>
					<?php
					if($card['card'] != NULL) {
						?>
						<div class="col-12 col-md-12 mt-2">
							<b>Card: </b><a href="<?= $card['card'] ?>"><?= $card['card'] ?></a>
						</div>
						<?php
					}
					?>
				</div>
				<?php
			}
			if($card['order'] == $_POST['pause']) {
				?>
				<div class="card-group row card-pause">
					<p>
						Intervalo
					</p>
				</div>
				<?php
			}
			?>
			<?php
		}
		?>
	</div>
