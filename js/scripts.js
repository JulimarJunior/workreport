var pauseCard = '<div class="card-group card-pause row"><button class="btn-remove btn-item" type="button" onclick="removePause()">+</button><button class="btn-more btn-item" type="button" onclick="addCard(999)">+</button><p>Intervalo</p></div>';
var pauseMax = 1;
var pauseCurrent = 0;
var pausePosCard = null;
var lastCard = 1;

function addPause(id) {
	if(pauseMax > pauseCurrent) {
		$('.card-'+id).after(pauseCard);
		pauseCurrent ++;
		pausePosCard = id;
	} else {
		alert("Apenas um intervalo é permitido");
	}
}

function removePause() {
	$('.card-pause').remove();
	pauseCurrent --;
	pausePosCard = null;
}

function addCard(id) {
	if(id == 999) {
		id = 'pause';
	}
	var codeCard = '<div class="card-group card-'+lastCard+' row">';
		codeCard += '<input type="number" class="order-card" value="'+lastCard+'" name="card['+lastCard+'][order]">'
		codeCard += '<button class="btn-more btn-item" type="button" onclick="addCard('+lastCard+')">+</button>';
		codeCard += '<button class="btn-remove btn-item" type="button" onclick="removeCard('+lastCard+')">+</button>';
		codeCard += '<button class="btn-pause btn-item" type="button" onclick="addPause('+lastCard+')">+</button>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Início</label>';
		codeCard += 		'<input type="time" name="card['+lastCard+'][start]" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Final</label>';
		codeCard += 		'<input type="time" name="card['+lastCard+'][end]" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Serviço</label>';
		codeCard += 		'<input type="text" name="card['+lastCard+'][service]" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="">Card Trello</label>';
		codeCard += 		'<input type="text" name="card['+lastCard+'][card]" class="form-item">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard += '<div class="col-md-12">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Descrição</label>';
		codeCard += 		'<textarea name="card['+lastCard+'][description]" class="form-item"></textarea>';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard += '</div>';

	$('.card-'+id).after(codeCard);
	lastCard++;
}

function removeCard(id) {
	if(id != 0) {
		$('.card-'+id).remove();
	}
}

$('#generate').validate({
    rules: {
    },
    messages: {
    },
    submitHandler: function(form) {
      var dados = $(form).serialize();
      $('#form-sugestao button').text("Enviando...");
      $('#form-sugestao button').attr("disabled", true);

      $.ajax({
        type: 'POST',
        url: document.location.origin + '/relatorios/php/generate.php',
        data: dados+'&pause='+pausePosCard,
        success: function(response) {
        	$('#generate').hide();
        	$('#report .content').html(response);
        	$('#report').show();
        }
      })
    }
});

function saveReport() {
	var date_report = $('#report .content .date_report').text();
	var dados = $('#report .content .report').html();
		dados = dados.replace('&','nArray');
		dados = 'text='+dados+'&date='+date_report;
	console.log(dados);
	$.ajax({
		type: 'POST',
		url: document.location.origin + '/relatorios/php/save.php',
		data: dados,
		success: function(response) {
		}
	})
}

function editReport() {
	$('#generate').show();
    $('#report .content').html('');
    $('#report').hide();
}