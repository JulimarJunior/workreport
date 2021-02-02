<?php require_once('php/header.php'); ?>

<?php 
	require_once('php/functions.php');
	verifyLogin();

	require_once('php/connection.php');
	$conn = Database::connectionPDO();

	try {
		$code = $conn->prepare("SELECT u.nm_usuario AS name, u.ds_email AS email, u.ds_imagem AS image, u.cd_cargo AS office FROM tb_usuario AS u WHERE u.cd_usuario = :user ");
		$code->bindParam(':user',($_SESSION['user']));
		$code->execute();
		$user = $code->fetch(PDO::FETCH_ASSOC);

		$code = $conn->prepare("SELECT * FROM tb_remetente ORDER BY nm_remetente");
		$code->execute();
		$senders = $code->fetchAll(PDO::FETCH_ASSOC);

		$code = $conn->prepare("SELECT cd_remetente FROM tb_remetente_usuario WHERE cd_usuario = :user");
		$code->bindParam(':user',($_SESSION['user']));
		$code->execute();
		$senders_on_before = $code->fetchAll(PDO::FETCH_ASSOC);
		$senders_on = array();
		foreach($senders_on_before as $sender_on_before) {
			array_push($senders_on, $sender_on_before['cd_remetente']);
		}

		$code = $conn->prepare("SELECT cd_cargo, nm_cargo FROM tb_cargo ORDER BY nm_cargo");
		$code->execute();
		$offices = $code->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};
?>

	<div class="container account" style="padding: 0">
		<form id="account" class="mt-4" enctype="multipart/form-data">
			<div class="row">
				<div class="col-12">
					<h1>Alterar dados pessoais</h1>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">Nome completo</label>
						<input type="text" class="form-item" name="name" value="<?= $user['name'] ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">E-mail</label>
						<input type="text" class="form-item" name="email" value="<?= $user['email'] ?>">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="required">Cargo</label>
						<select name="office" class="form-item">
							<?php
								foreach ($offices as $office) {
									?>
									<option <?= ($office['cd_cargo'] == $user['office']) ? 'selected' : '' ?> value="<?= $office['cd_cargo'] ?>"><?= $office['nm_cargo'] ?></option>
									<?php
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-6" style="padding-left: 0">
					<div class="col-12">
						<h1>Alterar senha</h1>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Nova senha</label>
							<input type="password" name="passwordNew" id="passwordNew" class="form-item">
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Confirmar nova senha</label>
							<input type="password" name="passwordConfirm" class="form-item">
						</div>
					</div>
				</div>
				<div class="col-md-6" style="padding-right: 0">
					<div class="col-12">
						<h1>Alterar foto de perfil</h1>
					</div>
					<div class="row">
						<div class="col-md-6 image-user-input">
							<div class="form-group" style="margin-left: 15px">
								<label for="">Foto</label>
								<input type="file" id="upload" class="form-item" name="image" style="display: none;" onchange="previewImage()">
								<br>
								<button type="button" class="btn-upload btn btn-color01" onclick="selectImage()">Escolher imagem</button>
							</div>
						</div>
						<div class="image-user-preview col-md-6 text-right mt-2" style="display: none;">
							<img src="" alt="">
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12">
					<h1>Destinatários</h1>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label for="">E-mails</label>
						<select name="senders[]" id="" class="form-item" multiple style="height: auto">
							<?php
								foreach($senders as $sender) {
									?>
									<option value="<?= $sender['cd_remetente'] ?>" <?= (in_array($sender['cd_remetente'],$senders_on)) ? 'selected' : '' ?>   ><?= $sender['nm_remetente'] ?> - <?= $sender['ds_email'] ?></option>
									<?php
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="" class="required">Senha atual</label>
						<input type="password" name="password" class="form-item">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button type="submit" class="btn btn-color01 w-100 mt-2">
						Salvar
					</button>
					<div class="alert alert-msg mt-3" role="alert">
					</div>
				</div>
			</div>
		</form>
	</div>
<?php require_once('php/footer.php'); ?>