<?php require_once('php/header.php'); ?>


<?php
	require_once('php/connection.php');
	$conn = Database::connectionPDO();

	if(isset($_GET['date'])) {
		$date = $_GET['date'];
	} else {
		$date = date('Y-m-d');
	}

	$user = $_SESSION['user'];

	try {
		$code = $conn->prepare("SELECT r.qt_pause AS pause, i.* FROM tb_relatorio AS r JOIN tb_item_relatorio AS i ON r.cd_relatorio = i.cd_relatorio WHERE r.cd_usuario = :user AND r.dt_relatorio = :date_report ORDER BY i.hr_inicio");
		$code->bindParam(':user',$user);
		$code->bindParam(':date_report',$date);
		$code->execute();
		$cards = $code->fetchAll(PDO::FETCH_ASSOC);
		$count_cards = count($cards);
		if($cards != NULL) {
			$pause = $cards[0]['pause'];
		}
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	}
?>
	
	<div class="container pt-5 pb-5">
		<div id="report" style="display: none">
			<div class="content"></div>
			<!-- <button class="w-100 btn-success btn mt-4" onclick="saveReport()">Salvar relatório</button> -->
			<div class="row">
				<button class="w-100 btn-danger btn mt-2" onclick="editReport()">Editar relatório</button>
			</div>
		</div>
		<form id="generate">
			<div class="row">
				<div class="col-md-10 mb-4 my-auto">
					<div class="loading-save">
						Aguardando
					</div>
				</div>
				<div class="col-md-2 mb-4 text-right">
					<div class="form-group" style="margin-bottom: 0">
						<label for="" class="required">Data do Relatório</label>
						<input type="date" class="form-item input-date" name="date" onfocus="setDate(value)" onblur="reloadDate(value)" value="<?= $date ?>">
					</div>
				</div>
			</div>
			<div class="card-group card-0 row">
				<input type="number" class="order-card" value="0" name="card[0][order]">
				<button class="btn-more btn-item" type="button" onclick="addCard(0)">
					<i class="fas fa-plus"></i>
				</button>
				<button class="btn-pause btn-item" type="button" onclick="addPause(0)">
					<i class="fas fa-pause"></i>
				</button>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Início</label>
						<input type="time" name="card[0][start]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-start">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Final</label>
						<input type="time" name="card[0][end]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-end">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">Serviço</label>
						<input type="text" name="card[0][service]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-service">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Card Trello</label>
						<input type="text" name="card[0][card]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-card">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="" class="required">Descrição</label>
						<textarea name="card[0][description]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-description"></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<button class="w-100 btn-success btn mt-4">Salvar relatório</button>
			</div>
			
		</form>
	</div>


<?php require_once('php/footer.php'); ?>

<script>
	var i = 0;
	$(document).ready(function(){
		<?php
			foreach ($cards as $card) {
				?>
					if(i != 0) {
						i_temp = i - 1;
						addCard(i_temp);
					}
					$('.card-'+i+' .input-start').val('<?= $card['hr_inicio'] ?>');
					$('.card-'+i+' .input-end').val('<?= $card['hr_final'] ?>');
					$('.card-'+i+' .input-service').val('<?= $card['ds_servico'] ?>');
					$('.card-'+i+' .input-card').val('<?= $card['ds_card'] ?>');
					$('.card-'+i+' .input-description').val('<?= $card['ds_descricao'] ?>');
					i ++;
				<?php
			}
			if($pause != NULL) {
				?>
				addPause(<?= $pause ?>);
				<?php
			}
			if(isset($_GET['view']) && $_GET['view'] == 'true') {
				?>
				$('#generate').submit();
				<?php
			} 
		?>
	});
</script>