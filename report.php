<?php require_once('php/header.php'); ?>

<?php
	$today = date('Y-m-d');
?>
	
	<div class="container pt-5 pb-5">
		<div id="report" style="display: none">
			<div class="content"></div>
			<button class="w-100 btn-success btn mt-4" onclick="saveReport()">Salvar relatório</button>
			<button class="w-100 btn-danger btn mt-2" onclick="editReport()">Editar relatório</button>
		</div>
		<form id="generate">
			<div class="row">
				<div class="col-md-2 mb-4 pr-0 offset-md-10 text-right">
					<div class="form-group">
						<label for="" class="required">Data do Relatório</label>
						<input type="date" class="form-item" name="date" value="<?= $today ?>">
					</div>
				</div>
			</div>
			<div class="card-group card-0 row">
				<input type="number" class="order-card" value="0" name="card[0][order]">
				<button class="btn-more btn-item" type="button" onclick="addCard(0)">
					+
				</button>
				<button class="btn-pause btn-item" type="button" onclick="addPause(0)">
					+
				</button>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Início</label>
						<input type="time" name="card[0][start]" class="form-item">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Final</label>
						<input type="time" name="card[0][end]" class="form-item">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">Serviço</label>
						<input type="text" name="card[0][service]" class="form-item">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Card Trello</label>
						<input type="text" name="card[0][card]" class="form-item">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="" class="required">Descrição</label>
						<textarea name="card[0][description]"  class="form-item"></textarea>
					</div>
				</div>
			</div>
			<button class="w-100 btn-success btn mt-4">Gerar relatório</button>
		</form>
	</div>


<?php require_once('php/footer.php'); ?>