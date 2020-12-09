<?php require_once('php/header.php'); ?>

<?php
	$today = date('Y-m-d');
?>
	
	<button onclick="showOrder()">ver cards</button>

	<div class="container pt-5 pb-5">
		<form id="generate">
			<div class="row">
				<div class="col-md-2 mb-4 pr-0 offset-md-10 text-right">
					<div class="form-group">
						<label for="" class="required">Data do Relatório</label>
						<input type="date" class="form-item" value="<?= $today ?>">
					</div>
				</div>
			</div>
			<div class="card-group card-0 row">
				<button class="btn-more btn-item" type="button" onclick="addCard(0,false)">
					+
				</button>
				<button class="btn-remove btn-item" type="button" onclick="removeCard(0)">
					+
				</button>
				<button class="btn-pause btn-item" type="button" onclick="addPause(0)">
					+
				</button>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Início</label>
						<input type="time" class="form-item">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label for="" class="required">Horário Final</label>
						<input type="time" class="form-item">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">Serviço</label>
						<input type="text" class="form-item">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Card Trello</label>
						<input type="text" class="form-item">
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="">Observações</label>
						<textarea class="form-item"></textarea>
					</div>
				</div>
			</div>
		</form>
	</div>


<?php require_once('php/footer.php'); ?>