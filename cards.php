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
	<div id="handle-base">
          <div id="handle-cards">
          </div>
	</div>

	<div class="modal-fullpage" id="card-edit">
        	<div class="container">
            	<div class="row" style="width: 100%; height: 100vh">
                	<div class="mx-auto my-auto">
                    	<div class="modal-square">
                        		<div class="row modal-content-header" style="margin-left: 0px;">
                            		<div class="col-10">
                                		<h1></h1>
                            			<p class="queue"></p>             
                            		</div>
                            		<div class="col-2 text-right">
                                		<button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    		<i class="fas fa-times"></i>
                                		</button>
                            		</div>
                        		</div>
                        		<div class="p-3 modal-content-text">
                            		<p class="description"></p>             
                        		</div>
                    	</div>
               	</div>
            	</div>
        	</div>
    	</div>

	<div class="modal-fullpage" id="grid-edit">
        	<div class="container">
            	<div class="row" style="width: 100%; height: 100vh">
                	<div class="mx-auto my-auto">
                    	<div class="modal-square">
                        		<div class="row modal-content-header" style="margin-left: 0px;">
                            		<div class="col-10">
                                		<h1>Editar fila</h1>
                            		</div>
                            		<div class="col-2 text-right">
                                		<button onclick="closeModal()" class="btn btn-color01 btn-return">
                                    		<i class="fas fa-times"></i>
                                		</button>
                            		</div>
                        		</div>
                        		<div class="p-3 modal-content-text">
						    	<form id="queue-edit">
								<div class="form-group">
									<label for="" class="required">Nome da fila</label>
									<input type="text" name="name" class="form-item">
								</div>
								<button class="w-100 btn-color01 btn mt-4 btn-submit">Salvar fila</button>
							</form>
                        		</div>
                    	</div>
               	</div>
            	</div>
        	</div>
    	</div>

<?php require_once('php/footer.php'); ?>

<script>
    	lmdd.set(document.getElementById('handle-cards'), {
        	containerClass: 'handle-grid',
        	draggableItemClass: 'handle-item',
        	handleClass:false,
        	nativeScroll: true,
	   	dragstartTimeout: 150,
    	});

	var cardsInfos = [];
	var gridsInfos = [];
	var cardGrabbing;
	var reload = false;
	var lastUpdate;
	var gridEdit;

    	loadCards();

	function openCard(id) {
		$('.modal-content-header h1').text(cardsInfos[id].title);
		$('.modal-content-header .queue').html('Na lista <b>'+cardsInfos[id].queue+'</b>');
		$('.modal-content-text p.description').text(cardsInfos[id].description);
		showModal('card-edit');
	}

	function updateCard() {
		if(cardGrabbing != null) {
			var grid = $('.handle-item#'+cardGrabbing).parent().attr('id');
			grid = grid.replace('handle-grid-','');
			$('.handle-item#'+cardGrabbing).attr('data-queue', grid);
			cardGrabbing = cardGrabbing.replace('handle-item-','');
			cardsInfos[cardGrabbing].queue = gridsInfos[grid].title;
			cardsInfos[cardGrabbing].queueId = gridsInfos[grid].id;

			let order;
			let childrens = $('#handle-grid-'+grid).find('.handle-item');
			let after = false;
			let before = true;
			let orderAfter;
			let orderBefore;
			let saveCardId;
			for(let i = 0; i < childrens.length; i++) {
				orderBefore = i + 1;
				if(after) {
					let afterId = childrens[i].id.replace('handle-item-','');
					cardsInfos[afterId].order = orderAfter;
					orderAfter++;

					saveCardId = afterId--;
				}
				if(before) {
					let beforeId = childrens[i].id.replace('handle-item-','');
					cardsInfos[beforeId].order = orderBefore;
					orderBefore++;

					saveCardId = beforeId--;
				}
				if(childrens[i].id == 'handle-item-'+cardGrabbing) {
					order = i + 1;
					cardsInfos[cardGrabbing].order = order;
					after = true;
					before = false;
					orderAfter = order + 1;
					saveCardId = cardGrabbing;
				}
				if(saveCardId) {
					saveCard(saveCardId);
				}
			}
			cardGrabbing = null;
			order = null;
		}
	}

	function saveCard(id) {
		$.ajax({
			type: 'POST',
			url: document.location.origin + '/projects/workreport/php/save-card.php',
			data: cardsInfos[id],
			success: function() {
				// Sucesso
			}
		})
	}

	// Para fazer a atualização em tempo real é necessário enviar as variaveis "reload" como True e "lastUpdate" para load-cards.php, fazendo com que seja retornado os cards que sofreram atualizações desde a última consulta. Avaliar possibilidade de mudanças com requisições sendo enviadas pelo Servidor (webSocket).

	setInterval(function(){
		// return; // Desativar para fazer atualização em tempo real
		$.ajax({
			type: 'POST',
			url: document.location.origin + '/projects/workreport/php/load-cards.php',
			data: {
				reload: reload,
				lastUpdate: lastUpdate
			},
			success: function(response) {
				var obj = JSON.parse(response);
				lastUpdate = obj.update;

				delete obj.update;
				for(let prop in obj) {
					let grid = obj[prop];
					if(gridsInfos[grid.id].title != grid.title) {
						$('#handle-grid-'+grid.id+' .title').text(grid.title);
					}
					for(let prop in grid.cards) {
						let card = grid.cards[prop];
						$('#handle-item-'+card.id).remove();
						$('.handle-grid#handle-grid-'+grid.id).append('<div class="handle-item" onclick="openCard('+card.id+')" id="handle-item-'+card.id+'" data-queue="'+grid.id+'"><div class="content"><div class="task"><b>'+card.title+'</b><div class="row m-0"><div class="description col-2 pl-0"></div></div></div></div></div>');
						if(card.description) {
							$('.handle-grid#handle-grid-'+grid.id+' .content .description').html('<i title="Este cartão tem uma descrição" class="fas have-description fa-align-left"></i>');
						}
						cardsInfos[card.id] = ({
							'id'			: card.id,
							'title' 		: card.title,
							'description'  : card.description,
							'order'		: card.order,
							'queue'		: grid.title,
							'queueId'		: grid.id,
							'members'		: []
						})

						let membersHtml = "";
						for(let prop in card.members) {
							let member = card.members[prop];

							if(member) {
								membersHtml += '<div class="member-img" role="img" title="'+member.name+' | '+member.email+'" style="background-image: url(https://akirastudio.com.br/projects/workreport/img/user/'+member.image+')"></div>';
							}
						}
						$('.handle-grid#handle-grid-'+grid.id+' #handle-item-'+card.id+' .content .description').after('<div class="col-10 pr-0 members">'+membersHtml+'</div>');
					}
				}
				
				loadConfigs();
			}
		})
	},1000)

    	function loadCards(){
		$.ajax({
			type: 'POST',
			url: document.location.origin + '/projects/workreport/php/load-cards.php',
			data: {
				reload: reload,
				lastUpdate: lastUpdate
			},
			success: function(response) {
				var obj = JSON.parse(response);
				lastUpdate = obj.update;

				delete obj.update;
				for(let prop in obj) {
					let grid = obj[prop];
					$('#handle-cards').append('<div class="handle-grid" id="handle-grid-'+grid.id+'"><div class="row m-0 mb-2"><div data-grid="'+grid.id+'" class="title col-9 pl-0">'+grid.title+'</div><div class="col-3 pr-0 text-right"><i class="fas more-grid fa-plus"></i><i class="fas menu-grid fa-ellipsis-h"></i></div></div><div data-queue="'+grid.id+'"></div></div>');
					gridsInfos[grid.id] = ({
						'id'		: grid.id,
						'title' 	: grid.title
					})
					for(let prop in grid.cards) {
						let card = grid.cards[prop];
						$('.handle-grid#handle-grid-'+grid.id).append('<div class="handle-item" onclick="openCard('+card.id+')" id="handle-item-'+card.id+'" data-queue="'+grid.id+'"><div class="content"><div class="task"><b>'+card.title+'</b><div class="row m-0"><div class="description col-2 pl-0"></div></div></div></div></div>');
						if(card.description) {
							$('.handle-grid#handle-grid-'+grid.id+' .content .description').html('<i title="Este cartão tem uma descrição" class="fas have-description fa-align-left"></i>');
						}
						cardsInfos[card.id] = ({
							'id'			: card.id,
							'title' 		: card.title,
							'description'  : card.description,
							'order'		: card.order,
							'queue'		: grid.title,
							'queueId'		: grid.id,
							'members'		: []
						})

						let membersHtml = "";
						for(let prop in card.members) {
							let member = card.members[prop];

							if(member) {
								membersHtml += '<div class="member-img" role="img" title="'+member.name+' | '+member.email+'" style="background-image: url(https://akirastudio.com.br/projects/workreport/img/user/'+member.image+')"></div>';
							}
						}
						$('.handle-grid#handle-grid-'+grid.id+' #handle-item-'+card.id+' .content .description').after('<div class="col-10 pr-0 members">'+membersHtml+'</div>');
					}
				}
				
				loadConfigs();
				reload = true;
			}
		})
    	};

	function loadConfigs() {
		// Editar titulo da fila
		$('.handle-grid .title').click(function(){
			if(gridEdit == null) {
 				gridEdit = $(this).data('grid');
				$('#handle-grid-'+gridEdit+' .title').html('<input class="form-item" style="height: 24px;">');
				$('#handle-grid-'+gridEdit+' .title input').val(gridsInfos[gridEdit].title).focus();

				$('.handle-grid .title input').blur(function(){
					gridName = $('#handle-grid-'+gridEdit+' .title input').val();
					gridsInfos[gridEdit].title = gridName;
					$('#handle-grid-'+gridEdit+' .title').html(gridName);

					$.ajax({
						type: 'POST',
						url: document.location.origin + '/projects/workreport/php/save-queue.php',
						data: {
							id: gridEdit,
							name: gridName
						},
						success: function(response) {
							// Edição concluida
						}
					})
					gridEdit = null;
				})
			}
		})
	}
</script>