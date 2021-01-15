<?php 
	session_start();
	if(isset($_SESSION['user'])) {
		header('Location: list.php');
		exit;
	}
	session_destroy();
?>

<?php require_once('php/header.php'); ?>
<div class="fullpage">
	<div class="row">
		<div class="my-auto mx-auto">
			<div class="card-login">
				<form action="" id="login">

					<h1>
						<img src="img/logo.png" alt="Summer Comunicação" title="Summer Comunicação" style="width: 75px; margin-bottom: 15px">
						<br>
						Acessar conta
					</h1>
					<div class="form-group">
						<label for="" class="required">E-mail</label>
						<input type="text" class="form-item" name="email">
					</div>
					<div class="form-group">
						<label for="" class="required">Senha</label>
						<input type="password" class="form-item" name="password">
					</div>
					<div class="form-group">
						<button class="btn btn-color01 mt-3 w-100">
							Acessar
						</button>
					</div>
					<div class="alert alert-danger alert-msg" role="alert">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php require_once('php/footer.php'); ?>