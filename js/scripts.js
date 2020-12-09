var pauseCard = '<div class="card-group card-pause row"><button class="btn-remove btn-item" type="button" onclick="removePause()">+</button><p>Intervalo</p></div>';
var pauseMax = 1;
var pauseCurrent = 0;
var pausePosCard = null;
var orderCards = [0,0];
var lastCard = 1;

function addPause(id) {
	if(pauseMax > pauseCurrent) {
		$('.card-'+id).after(pauseCard);
		pauseCurrent ++;
		pausePosCard = id;
		addCard(id,true);
	} else {
		alert("Apenas um intervalo é permitido");
	}
}

function removePause() {
	$('.card-pause').remove();
	pauseCurrent --;
	pausePosCard = null;
}

function addCard(id,pos) {
	var codeCard = '<div class="card-group card-'+lastCard+' row">';
		codeCard += '<button class="btn-more btn-item" type="button" onclick="addCard('+lastCard+',false)">+</button>';
		codeCard += '<button class="btn-remove btn-item" type="button" onclick="removeCard('+lastCard+')">+</button>';
		codeCard += '<button class="btn-pause btn-item" type="button" onclick="addPause('+lastCard+')">+</button>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Início</label>';
		codeCard += 		'<input type="time" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Final</label>';
		codeCard += 		'<input type="time" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Serviço</label>';
		codeCard += 		'<input type="text" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Card Trello</label>';
		codeCard += 		'<input type="text" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard += '<div class="col-md-12">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="">Observações</label>';
		codeCard += 		'<textarea class="form-item"></textarea>';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard += '</div>';

	if(pos == false) {
		$('.card-'+id).after(codeCard);
	} else {
		$('.card-pause').after(codeCard);
	}
	lastCard++;
}

function showOrder() {
	console.log(orderCards);
}