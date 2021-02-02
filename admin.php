<?php require_once('php/header.php'); ?>

<?php 
	require_once('php/functions.php');
	verifyLogin();

	require_once('php/connection.php');
	$conn = Database::connectionPDO();

	try {
		$code = $conn->prepare("SELECT * FROM tb_cargo ORDER BY nm_cargo");
		$code->execute();
		$offices = $code->fetchAll(PDO::FETCH_ASSOC);

		$code = $conn->prepare("SELECT * FROM tb_servico ORDER BY nm_servico");
		$code->execute();
		$services = $code->fetchAll(PDO::FETCH_ASSOC);

		$code = $conn->prepare("SELECT * FROM tb_remetente ORDER BY nm_remetente");
		$code->execute();
		$senders = $code->fetchAll(PDO::FETCH_ASSOC);

		if(isset($_GET['office'])) {
			$code = $conn->prepare("SELECT u.cd_usuario AS id, u.nm_usuario AS name, u.ds_email AS email, c.nm_cargo AS office FROM tb_usuario AS u JOIN tb_cargo AS c ON u.cd_cargo = c.cd_cargo WHERE u.cd_cargo = :office");
			$code->bindParam(':office', ($_GET['office']));
		} else {
			$code = $conn->prepare("SELECT u.cd_usuario AS id, u.nm_usuario AS name, u.ds_email AS email, c.nm_cargo AS office FROM tb_usuario AS u JOIN tb_cargo AS c ON u.cd_cargo = c.cd_cargo");
		}
		$code->execute();
		$users = $code->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(Exception $e) {
	    echo $e->getMessage();
	};
?>

	<div class="container account" style="padding: 0">
		<?php
			if($_SESSION['adm'] == 1) {
				?>
					<div class="container" style="padding: 0">
						<div class="pb-3">
							<div class="row pt-3" id="listAccounts">
								<div class="col-md-3">
									<button class="btn btn-color01 w-100 mt-2" onclick="showCreateAccount()">
										Criar conta
									</button>
								</div>
								<div class="col-md-3">
									<button class="btn btn-color02 w-100 mt-2" onclick="showOfficesConfig()">
										Cargos
									</button>
								</div>
								<div class="col-md-3">
									<button class="btn btn-color02 w-100 mt-2" onclick="showServicesConfig()">
										Clientes
									</button>
								</div>
								<div class="col-md-3">
									<button class="btn btn-color02 w-100 mt-2" onclick="showSendersConfig()">
										Destinatário
									</button>
								</div>
								<div class="col-md-12">
									<div class="form-group mt-4">
										<label for="">Cargo</label>
										<select name="office" class="form-item" onchange="showUsersOffice(value)">
											<option value="all">Todos</option>
											<?php
												foreach ($offices as $office) {
													?>
													<option value="<?= $office['cd_cargo'] ?>" <?= (isset($_GET['office']) && $office['cd_cargo'] == $_GET['office'] ? 'selected' : '') ?>><?= $office['nm_cargo'] ?></option>
													<?php
												}
											?>
										</select>
									</div>
									<div class="alert alert-msg mt-3" role="alert">
									</div>
									<div class="users-list">
										<?php
											if($users != null) {
												foreach ($users as $user) {
													?>
													<div class="card-group pl-4 card-user-<?= $user['id'] ?>">
														<div class="row" style="width: 100%">
															<div class="col-6 nm_user my-auto">
																<p style="margin: 0"><?= $user['name'] ?></p>
																<span class="email-list d-none d-md-block"><?= $user['email'] ?></span>
															</div>
															<div class="col-6 text-right">
																<a href="list.php?id=<?= $user['id'] ?>"><button class="btn btn-color01"><i class="far fa-eye"></i></button></a>
																<button onclick="removeUser(<?= $user['id'] ?>)" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button>
															</div>
														</div>
													</div>
													<?php
												}
											} else {
												?>
												<div class="mt-3">
													Nenhuma informação encontrada
												</div>
												<?php
											}
										?>
									</div>
								</div>
							</div>
							<form id="register" class="mt-4" style="display: none;">
								<div class="row">
									<div class="col-12">
										<button onclick="returnAdm()" type="button" class="btn btn-color01 btn-return"><i class="fas fa-arrow-left"></i></button>
										<h1 style="display: inline;">Criar conta</h1>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">Nome completo</label>
											<input type="text" class="form-item" name="name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">E-mail</label>
											<input type="text" class="form-item" name="email">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">Cargo</label>
											<select name="office" class="form-item">
												<option value="" disabled selected>Selecione...</option>
												<?php
													foreach ($offices as $office) {
														?>
														<option value="<?= $office['cd_cargo'] ?>"><?= $office['nm_cargo'] ?></option>
														<?php
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">Administrador</label>
											<select name="adm" class="form-item">
												<option value="0">Não</option>
												<option value="0">Sim</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">Senha</label>
											<input type="password" name="passwordNew" id="passwordNew" class="form-item">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="" class="required">Confirmar senha</label>
											<input type="password" name="passwordConfirm" class="form-item">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="">E-mails destinatários</label>
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
											<label for="" class="required">Sua senha</label>
											<input type="password" name="password" class="form-item">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-color01 w-100 mt-2 btn-submit">
											Criar
										</button>
										<div class="alert alert-msg mt-3" role="alert">
										</div>
									</div>
								</div>
							</form>
							<div id="offices" style="display: none;">
								<div class="row">
									<div class="col-12 mt-4">
										<button onclick="returnAdm()" type="button" class="btn btn-color01 btn-return"><i class="fas fa-arrow-left"></i></button>
										<h1 style="display: inline;">Cargos</h1>
									</div>
								</div>
								<form id="office-edit" style="display: none;">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="" class="required">Nome do cargo</label>
												<input type="hidden" class="form-item" name="id">
												<input type="text" class="form-item" name="name">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Editar cargo
											</button>
											<button onclick="cancelEdit('office')" class="btn btn-color02 w-100 mt-2" type="button">
												Cancelar
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<form id="office-register">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="" class="required">Nome do cargo</label>
												<input type="text" class="form-item" name="name">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Criar cargo
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="offices-list col-12">
										<?php
											if($users != null) {
												foreach ($offices as $office) {
													?>
													<div class="card-group pl-4 card-office-<?= $office['cd_cargo'] ?>">
														<div class="row" style="width: 100%">
															<div class="col-6 my-auto nm_office">
																<?= $office['nm_cargo'] ?>
															</div>
															<div class="col-6 text-right">
																<button onclick="editOffice(<?= $office['cd_cargo'] ?>)" class="btn btn-color02 mr-1"><i class="fas fa-edit"></i></button>
																<button onclick="removeOffice(<?= $office['cd_cargo'] ?>)" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button>
															</div>
														</div>
													</div>
													<?php
												}
											} else {
												?>
												<div class="mt-3">
													Nenhuma informação encontrada
												</div>
												<?php
											}
										?>
									</div>
								</div>
							</div>
							<div id="services" style="display: none;">
								<div class="row">
									<div class="col-12 mt-4">
										<button onclick="returnAdm()" type="button" class="btn btn-color01 btn-return"><i class="fas fa-arrow-left"></i></button>
										<h1 style="display: inline;">Clientes</h1>
									</div>
								</div>
								<form id="service-edit" style="display: none;">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="" class="required">Nome do cliente</label>
												<input type="text" class="form-item" name="name">
												<input type="hidden" class="form-item" name="id">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Editar cliente
											</button>
											<button onclick="cancelEdit('service')" class="btn btn-color02 w-100 mt-2" type="button">
												Cancelar
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<form id="service-register">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="" class="required">Nome do cliente</label>
												<input type="text" class="form-item" name="name">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Criar cliente
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="services-list col-12">
										<?php
											if($services != null) {
												foreach ($services as $service) {
													?>
													<div class="card-group pl-4 card-service-<?= $service['cd_servico'] ?>">
														<div class="row" style="width: 100%">
															<div class="col-6 my-auto nm_service">
																<?= $service['nm_servico'] ?>
															</div>
															<div class="col-6 text-right">
																<button onclick="editService(<?= $service['cd_servico'] ?>)" class="btn btn-color02 mr-1"><i class="fas fa-edit"></i></button>
																<button onclick="removeService(<?= $service['cd_servico'] ?>)" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button>
															</div>
														</div>
													</div>
													<?php
												}
											} else {
												?>
												<div class="mt-3">
													Nenhuma informação encontrada
												</div>
												<?php
											}
										?>
									</div>
								</div>
							</div>
							<div id="senders" style="display: none;">
								<div class="row">
									<div class="col-12 mt-4">
										<button onclick="returnAdm()" type="button" class="btn btn-color01 btn-return"><i class="fas fa-arrow-left"></i></button>
										<h1 style="display: inline;">Destinatários</h1>
									</div>
								</div>
								<form id="sender-edit" style="display: none;">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="" class="required">Nome do destinatário</label>
												<input type="text" class="form-item" name="name">
												<input type="hidden" class="form-item" name="id">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="" class="required">E-mail do destinatário</label>
												<input type="email" class="form-item" name="email">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Editar destinatário
											</button>
											<button onclick="cancelEdit('sender')" class="btn btn-color02 w-100 mt-2" type="button">
												Cancelar
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<form id="sender-register">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="" class="required">Nome do destinatário</label>
												<input type="text" class="form-item" name="name">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="" class="required">E-mail do destinatário</label>
												<input type="email" class="form-item" name="email">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-color01 w-100 mt-2 btn-submit">
												Criar destinatário
											</button>
											<div class="alert alert-msg mt-3" role="alert">
											</div>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="senders-list col-12">
										<?php
											if($senders != null) {
												foreach ($senders as $sender) {
													?>
													<div class="card-group pl-4 card-sender-<?= $sender['cd_remetente'] ?>">
														<div class="row" style="width: 100%">
															<div class="col-6 my-auto nm_sender">
																<p style="margin: 0"><?= $sender['nm_remetente'] ?></p>
																<span class="email-list d-none d-md-block"><?= $sender['ds_email'] ?></span>
															</div>
															<div class="col-6 text-right">
																<button onclick="editSender(<?= $sender['cd_remetente'] ?>)" class="btn btn-color02 mr-1"><i class="fas fa-edit"></i></button>
																<button onclick="removeSender(<?= $sender['cd_remetente'] ?>)" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button>
															</div>
														</div>
													</div>
													<?php
												}
											} else {
												?>
												<div class="mt-3">
													Nenhuma informação encontrada
												</div>
												<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>						
					</div>
				<?php
			} else {
				?>
					<div class="text-center pt-5 pb-5">
						<h3>Sem permissão</h3>
					</div>
				<?php
			}
		?>
	</div>
	<div class="modal-fullpage" id="remove-office">
        <div class="container">
            <div class="row" style="width: 100%; height: 100vh">
                <div class="mx-auto my-auto">
                    <div class="modal-square">
                        <div class="row modal-content-header" style="margin-left: 0px;">
                            <div class="col-10">
                                <h1>Remover cargo</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            Você tem certeza que deseja remover o cargo <b></b>?
                            <button class="btn btn-color01 w-100 mt-3" onclick="confirmRemoveOffice()">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-fullpage" id="remove-sender">
        <div class="container">
            <div class="row" style="width: 100%; height: 100vh">
                <div class="mx-auto my-auto">
                    <div class="modal-square">
                        <div class="row modal-content-header" style="margin-left: 0px;">
                            <div class="col-10">
                                <h1>Remover destinatário</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            Você tem certeza que deseja remover o destinatário <b></b>?
                            <button class="btn btn-color01 w-100 mt-3" onclick="confirmRemoveSender()">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-fullpage" id="remove-service">
        <div class="container">
            <div class="row" style="width: 100%; height: 100vh">
                <div class="mx-auto my-auto">
                    <div class="modal-square">
                        <div class="row modal-content-header" style="margin-left: 0px;">
                            <div class="col-10">
                                <h1>Remover cliente</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            Você tem certeza que deseja remover o cliente <b></b>?
                            <button class="btn btn-color01 w-100 mt-3" onclick="confirmRemoveService()">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-fullpage" id="remove-user">
        <div class="container">
            <div class="row" style="width: 100%; height: 100vh">
                <div class="mx-auto my-auto">
                    <div class="modal-square">
                        <div class="row modal-content-header" style="margin-left: 0px;">
                            <div class="col-10">
                                <h1>Remover usuário</h1>
                            </div>
                            <div class="col-2 text-right">
                                <button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-3">
                            <p>
                            	Você tem certeza que deseja remover o usuário <b></b>?
                            </p>
                            <p>
                            	Isso fará com que todos os relatórios gerados pelo usuário sejam excluídos!
                            </p>
                            <button class="btn btn-color01 w-100 mt-3" onclick="confirmRemoveUser()">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once('php/footer.php'); ?>
