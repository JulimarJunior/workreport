var pauseCard = '<div class="card-group card-pause row"><button class="btn-remove btn-item" type="button" onclick="removePause()"><i class="fas fa-trash"></i></button><button class="btn-more btn-item" type="button" onclick="addCard(999)"><i class="fas fa-plus"></i></button><p>Intervalo</p></div>';
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
		codeCard += '<button class="btn-more btn-item" type="button" onclick="addCard('+lastCard+')"><i class="fas fa-plus"></i></button>';
		codeCard += '<button class="btn-remove btn-item" type="button" onclick="removeCard('+lastCard+')"><i class="fas fa-trash"></i></button>';
		codeCard += '<button class="btn-pause btn-item" type="button" onclick="addPause('+lastCard+')"><i class="fas fa-pause"></i></button>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Início</label>';
		codeCard += 		'<input type="time" name="card['+lastCard+'][start]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-start">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-2">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Horário Final</label>';
		codeCard += 		'<input type="time" name="card['+lastCard+'][end]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-end">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Serviço</label>';
		codeCard += 		'<input type="text" name="card['+lastCard+'][service]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-service">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard +=	'<div class="col-md-4">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="">Card Trello</label>';
		codeCard += 		'<input type="text" name="card['+lastCard+'][card]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-card">';
		codeCard += 	'</div>';
		codeCard += '</div>';
		codeCard += '<div class="col-md-12">';
		codeCard += 	'<div class="form-group">';
		codeCard += 		'<label for="" class="required">Descrição</label>';
		codeCard += 		'<textarea name="card['+lastCard+'][description]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-description"></textarea>';
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
        	saveReport();
        }
      })
    }
});

function saveReport() {
	var dados = $('#generate').serialize();
	$.ajax({
		type: 'POST',
		url: document.location.origin + '/relatorios/php/save.php',
		data: dados+'&pause='+pausePosCard,
		success: function(response) {
		}
	})
}

function editReport() {
	$('#generate').show();
    $('#report .content').html('');
    $('#report').hide();
}

var load = null;

function blurSave() {
	loadSave(5);
}

function focusSave() {
	$('.loading-save').text('Aguardando');
	if(load != null) {
		clearInterval(load);
	}
}

function loadSave(time_max) {
 	$('.loading-save').html('Salvando automaticamente em <b>5</b> segundos');
	var time = time_max;
	load = setInterval(function(){
		if(time != 0) {
			time --;
			$('.loading-save b').text(time);
		} else {
			saveReport();
			$('.loading-save').text('Salvo com sucesso');
			clearInterval(load);
		}
	}, 1000);
}

var date_report;

function setDate(value) {
	date_report = value;
}

function reloadDate(value) {
	if(value != date_report) {
		window.location.href = "report.php?date="+value;
	}
}