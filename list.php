<?php require_once('php/header.php'); ?>

<?php
	require_once('php/connection.php');
	$conn = Database::connectionPDO();

	$user = $_SESSION['user'];

	try {
		$code = $conn->prepare("SELECT dt_relatorio FROM tb_relatorio WHERE cd_usuario = :user ORDER BY dt_relatorio DESC");
		$code->bindParam(':user',$user);
		$code->execute();
		$reports = $code->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}
?>

	<div class="container pt-4">
		<div class="row">
			<a href="" class="w-100 mb-3">
				<button class="btn btn-warning w-100">
					Fazer relatório
				</button>
			</a>
		</div>
		<?php
			foreach ($reports as $report) {
				?>
				<div class="card-group mb-2 row">
					<div class="col-md-6 my-auto">
						<b><?= date('d/m/Y', strtotime($report['dt_relatorio'])) ?></b>
					</div>
					<div class="col-md-6 my-auto text-right">
						<a href="report.php?date=<?= $report['dt_relatorio'] ?>&view=true"><button class="btn btn-success">Visualizar</button></a>
						<a href="report.php?date=<?= $report['dt_relatorio'] ?>"><button class="btn btn-info">Editar relatório</button></a>
					</div>
				</div>
				<?php
			}
		?>
	</div>

<?php require_once('php/footer.php'); ?>