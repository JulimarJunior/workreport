<?php require_once('php/header.php'); ?>

<?php 
	require_once('php/functions.php');
	verifyLogin();
?>

<?php
	require_once('php/connection.php');
	$conn = Database::connectionPDO();

	if($_SESSION['adm'] == 1 && isset($_GET['id'])) {
		$user = $_GET['id'];
		$_SESSION['view'] = $_GET['id'];
	} else {
		$user = $_SESSION['user'];
		$_SESSION['view'] = false;
	}

	try {
		$code = $conn->prepare("SELECT dt_relatorio FROM tb_relatorio WHERE cd_usuario = :user ORDER BY dt_relatorio DESC LIMIT 10");
		$code->bindParam(':user',$user);
		$code->execute();
		$reports = $code->fetchAll(PDO::FETCH_ASSOC);

		$code = $conn->prepare("SELECT nm_usuario FROM tb_usuario WHERE cd_usuario = :user");
		$code->bindParam(':user',$user);
		$code->execute();
		$user_report = $code->fetchColumn();
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}
?>

	<div class="container pt-4">
		<div class="row">
			<?php
				if(!$_SESSION['view']) {
					?>
					<a href="report.php" class="w-100 mb-3">
						<button class="btn btn-color01 w-100">
							Fazer relatório
						</button>
					</a>
					<?php
				} else {
					?>
					<a href="javascript:history.back()"><button type="button" class="btn btn-color01 btn-return"><i class="fas fa-arrow-left"></i></button></a>
					<h1 style="font-size: 24px; display: inline;">Relatório de <b style="color: var(--color03)"><?= $user_report ?></b></h1>
					<?php
				}
			?>
		</div>
		<?php
			if($reports) {
				foreach ($reports as $report) {
					?>
					<div class="card-group mb-2 row">
						<div class="col-md-6 my-auto">
							<b><?= date('d/m/Y', strtotime($report['dt_relatorio'])) ?></b>
						</div>
						<div class="col-md-6 my-auto text-right">
							<?php
								if(!$_SESSION['view']) {
									?>
									<a href="report.php?date=<?= $report['dt_relatorio'] ?>"><button class="btn btn-color02"><i class="fas fa-edit"></i></button></a>
									<a href="report.php?date=<?= $report['dt_relatorio'] ?>&view=true"><button class="btn btn-color01"><i class="far fa-eye"></i></button></a>
									<?php
								} else {
									?>
									<a href="report.php?date=<?= $report['dt_relatorio'] ?>&view=true&id=<?= $user ?>"><button class="btn btn-color01"><i class="far fa-eye"></i></button></a>
									<?php
								}
							?>
							
						</div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="row">
					Nenhum relatório encontrado
				</div>
				<?php
			}

		?>
	</div>

<?php require_once('php/footer.php'); ?>