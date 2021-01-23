var pauseCard = '<div class="card-group card-pause row"><button class="btn-remove btn-item" type="button" onclick="removePause()"><i class="fas fa-trash"></i></button><button class="btn-more btn-item" type="button" onclick="addCard(999)"><i class="fas fa-plus"></i></button><p>Intervalo</p></div>';
var pauseMax = 1;
var pauseCurrent = 0;
var pausePosCard = null;
var lastCard = 1;
var imageUser = '';
var removingOffice;
var removingService;
var removingUser;

function addPause(id) {
	if(pauseMax > pauseCurrent) {
		$('.card-'+id).after(pauseCard);
		pauseCurrent ++;
		pausePosCard = id;
	} else {
		alert("Apenas um intervalo é permitido");
        showModal('alert-pause');
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
		codeCard += 		'<label for="" class="required">Cliente</label>';
		codeCard += 		'<input type="text" list="servicesList" name="card['+lastCard+'][service]" onfocus="focusSave()" onblur="blurSave()" class="form-item input-service">';
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

        $.ajax({
            type: 'POST',
            url: document.location.origin + '/relatorios/php/generate.php',
            data: dados+'&pause='+pausePosCard,
            success: function(response) {
            	$('#generate').fadeOut(150);
            	$('#report .content').html(response);
            	$('#report').delay(150).fadeIn(150);
        	   saveReport();
            }
        })
    }
});

$('#login').validate({
    rules: {
    	'email': {
    		required: true,
    		email: true
    	},
    	'password': {
    		required: true,
    		minlength: 8
    	}
    },
    messages: {
    	'email': {
    		required: 'Informe um e-Mail',
    		email: 'Informe um e-Mail válido'
    	},
    	'password': {
    		required: 'Informe uma senha',
    		minlength: 'Informe uma senha maior'
    	}
    },
    submitHandler: function(form) {
	    var dados = $(form).serialize();
	    loadingButton('#login button');

	    $.ajax({
	        type: 'POST',
	        url: document.location.origin + '/relatorios/php/login.php',
	        data: dados,
	        success: function(response) {
        	if(response == true) {
        		window.location.href = "list.php";
        	} else {
        		showAlert('.alert-msg',response,'error');
        		returnButton('Acessar','#login button');
        	}
        },
        error: function(){
        	returnButton('Acessar','#login button');
        }
      })
    }
});

$('#office-register').validate({
    rules: {
        'name': {
            required: true
        }
    },
    messages: {
        'name': {
            required: 'Informe um nome'
        }
    },
    submitHandler: function(form) {
        var dados = $(form).serialize();
        loadingButton('#office-register button.btn-submit');

        $.ajax({
            type: 'POST',
            url: document.location.origin + '/relatorios/php/register-office.php',
            data: dados,
            success: function(response) {
                $('input').val('');
                var obj = JSON.parse(response);
                returnButton('Criar cargo','#office-register button.btn-submit');
                if(obj.erro) {
                    if(obj.msg == 'existe') {
                        showAlert('.alert-msg','Cargo já existente','error');
                    }
                } else {
                    var item = '<div class="card-group pl-4 card-office-'+obj.id+'"><div class="row" style="width: 100%"><div class="col-6 my-auto nm_office">'+obj.name+'</div><div class="col-6 text-right"><button onclick="editOffice('+obj.id+')" class="btn btn-color02 mr-2"><i class="fas fa-edit"></i></button><button onclick="removeOffice('+obj.id+')" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button></div></div></div>';
                    $('.offices-list').prepend(item);
                    showAlert('.alert-msg','Cargo criado com sucesso','success');
                }
            },
            error: function(){
                returnButton('Criar cargo','#office-register button.btn-submit');
            }
        })
    }
});

$('#office-edit').validate({
    rules: {
        'name': {
            required: true
        }
    },
    messages: {
        'name': {
            required: 'Informe um nome'
        }
    },
    submitHandler: function(form) {
        var dados = $(form).serialize();
        loadingButton('#office-edit button.btn-submit');

        $.ajax({
            type: 'POST',
            url: document.location.origin + '/relatorios/php/register-office.php',
            data: dados,
            success: function(response) {
                returnButton('Editar cargo','#office-edit button.btn-submit');
                var obj = JSON.parse(response);
                if(obj.erro) {
                    if(obj.msg == 'existe') {
                        showAlert('.alert-msg','Cargo já existente ou nome sem mudanças','error');
                    } else {
                        showAlert('.alert-msg','Algo deu errado, tente novamente','error');
                    }
                } else {
                    $('.card-office-'+obj.id+' .nm_office').text(obj.name);
                    cancelEdit('office');
                    showAlert('.alert-msg','Cargo editado com sucesso','success');
                }
            },
            error: function(){
                returnButton('Editar cargo','#office-edit button.btn-submit');
            }
        })
    }
});

$('#service-register').validate({
    rules: {
        'name': {
            required: true
        }
    },
    messages: {
        'name': {
            required: 'Informe um nome'
        }
    },
    submitHandler: function(form) {
        var dados = $(form).serialize();
        loadingButton('#service-register button.btn-submit');

        $.ajax({
            type: 'POST',
            url: document.location.origin + '/relatorios/php/register-service.php',
            data: dados,
            success: function(response) {
                $('input').val('');
                returnButton('Criar cliente','#service-register button.btn-submit');
                var obj = JSON.parse(response);
                if(obj.erro) {
                    if(obj.msg == 'existe') {
                        showAlert('.alert-msg','Cliente já existente','error');
                    } else {
                        showAlert('.alert-msg','Algo deu errado, tente novamente','error');
                    }
                } else {
                    var item = '<div class="card-group pl-4 card-service-'+obj.id+'"><div class="row" style="width: 100%"><div class="col-6 my-auto nm_service">'+obj.name+'</div><div class="col-6 text-right"><button onclick="editService('+obj.id+')" class="btn btn-color02 mr-2"><i class="fas fa-edit"></i></button><button onclick="removeService('+obj.id+')" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button></div></div></div>';
                    $('.services-list').prepend(item);
                    showAlert('.alert-msg','Cliente criado com sucesso','success');
                }
            },
            error: function(){
                returnButton('Criar cliente','#service-register button.btn-submit');
            }
        })
    }
});

$('#service-edit').validate({
    rules: {
        'name': {
            required: true
        }
    },
    messages: {
        'name': {
            required: 'Informe um nome'
        }
    },
    submitHandler: function(form) {
        var dados = $(form).serialize();
        loadingButton('#service-edit button.btn-submit');

        $.ajax({
            type: 'POST',
            url: document.location.origin + '/relatorios/php/register-service.php',
            data: dados,
            success: function(response) {
                returnButton('Editar cliente','#service-edit button.btn-submit');
                var obj = JSON.parse(response);
                if(obj.erro) {
                    if(obj.msg == 'existe') {
                        showAlert('.alert-msg','Cliente já existente ou nome sem mudanças','error');
                    } else {
                        showAlert('.alert-msg','Algo deu errado, tente novamente','error');
                    }
                } else {
                    $('.card-service-'+obj.id+' .nm_service').text(obj.name);
                    cancelEdit('service');
                    showAlert('.alert-msg','Cliente editado com sucesso','success');
                }
            },
            error: function(){
                returnButton('Editar cliente','#service-edit button.btn-submit');
            }
        })
    }
});

var account = $('#account').validate({
    rules: {
    	'name': {
    		required: true,
    		minlength: 3
    	},
    	'email': {
    		required: true,
    		email: true
    	},
    	'password': {
    		required: true,
    		minlength: 8
    	},
    	'office': {
    		required: true
    	},
    	'passwordConfirm': {
    		equalTo: '#passwordNew',
    		minlength: 8
    	},
    	'passwordNew': {
    		minlength: 8
    	}
    },
    messages: {
    	'name': {
    		required: 'Informe um nome',
    		minlength: 'Informe um nome válido'
    	},
    	'email': {
    		required: 'Informe um e-Mail',
    		email: 'Informe um e-Mail válido'
    	},
    	'password': {
    		required: 'Informe uma senha',
    		minlength: 'Informe uma senha maior'
    	},
    	'office': {
    		required: 'Informe um cargo'
    	},
    	'passwordConfirm': {
    		equalTo: 'As senhas não coincidem',
    		minlength: 'Informe uma senha maior'
    	},
    	'passwordNew': {
    		minlength: 'Informe uma senha maior'
    	}
    },
    submitHandler: function(form) {
        var dados = new FormData($('#account')[0]);
	    loadingButton('#account button[type="submit"]');

	    hideAlert('.alert-msg');

	    $.ajax({
	        type: 'POST',
	        url: document.location.origin + '/relatorios/php/account.php',
	        data: dados,
            contentType: false,
            processData: false,
	        success: function(response) {
        	if(response == true) {
        		$('input[type="password"]').val('').blur();
                if(imageUser) {
                    $('.image-user-navbar').attr('src',imageUser);
                }
                $('.image-user-preview').hide();
                $('.btn-upload').text('Escolher imagem');
        		account.resetForm();
        		$('.name-user span b').text($('input[name="name"]').val())
        		showAlert('.alert-msg','Conta editada com sucesso','success');
        		returnButton('Salvar','#account button[type="submit"]');
        	} else {
        		showAlert('.alert-msg',response,'error');
        		returnButton('Salvar','#account button[type="submit"]');
        	}
        },
        error: function(){
        	returnButton('Salvar','#account button[type="submit"]');
        }
      })
    }
});

var register = $('#register').validate({
    rules: {
    	'name': {
    		required: true,
    		minlength: 3
    	},
    	'email': {
    		required: true,
    		email: true
    	},
    	'password': {
    		required: true,
    		minlength: 8
    	},
    	'office': {
    		required: true
    	},
    	'adm': {
    		required: true
    	},
    	'passwordConfirm': {
    		equalTo: '#passwordNew',
    		minlength: 8,
    		required: true
    	},
    	'passwordNew': {
    		minlength: 8,
    		required: true
    	}
    },
    messages: {
    	'name': {
    		required: 'Informe um nome',
    		minlength: 'Informe um nome válido'
    	},
    	'email': {
    		required: 'Informe um e-Mail',
    		email: 'Informe um e-Mail válido'
    	},
    	'password': {
    		required: 'Informe uma senha',
    		minlength: 'Informe uma senha maior'
    	},
    	'office': {
    		required: 'Informe um cargo'
    	},
    	'adm': {
    		required: 'Informe se é ou não um administrador'
    	},
    	'passwordConfirm': {
    		equalTo: 'As senhas não coincidem',
    		minlength: 'Informe uma senha maior',
    		required: 'Informe uma senha'
    	},
    	'passwordNew': {
    		minlength: 'Informe uma senha maior',
    		required: 'Confirma a senha',
    	}
    },
    submitHandler: function(form) {
	    var dados = $(form).serialize();
	    loadingButton('#register button.btn-submit');

	    hideAlert('.alert-msg');

	    $.ajax({
	        type: 'POST',
	        url: document.location.origin + '/relatorios/php/register.php',
	        data: dados,
	        success: function(response) {
                returnButton('Criar','#register button.btn-submit');
                var obj = JSON.parse(response);
                if(obj.erro) {
                    if(obj.msg == 'campos') {
                        showAlert('.alert-msg','Preencha todos os campos obrigatórios','error');
                    }
                    if(obj.msg == 'email_summer') {
                        showAlert('.alert-msg','Informe um email Summer','error');
                    }
                    if(obj.msg == 'senhas_nao_coincidem') {
                        showAlert('.alert-msg','As senhas não coincidem','error');
                    }
                    if(obj.msg == 'senha_incorreta') {
                        showAlert('.alert-msg','Sua senha está incorreta','error');
                    }
                    if(obj.msg == 'email_cadastrado') {
                        showAlert('.alert-msg','E-mail informado já está em uso','error');
                    }
                } else {
                    var office = $('select[name="office"]').val();
                    if(office == obj.office || office == 'all') {
                        var item = '<div class="card-group pl-4 card-user-'+obj.id+'"><div class="row" style="width: 100%"><div class="col-6 nm_user my-auto"><p style="margin: 0">'+obj.name+'</p><span class="email-list d-none d-md-block">'+obj.email+'</span></div><div class="col-6 text-right"><a href="list.php?id='+obj.id+'"><button class="btn btn-color01 mr-2"><i class="far fa-eye"></i></button></a><button onclick="removeUser('+obj.id+')" class="btn btn-color01 btn-remove"><i class="far fa-trash-alt"></i></button></div></div></div>';
                        $('.users-list').prepend(item);
                    }
                    showAlert('.alert-msg','Conta criada com sucesso','success');
                    $('input').val('').blur();
                    register.resetForm();
                }
            	// if(response == true) {
            	// 	$('input').val('').blur();
            	// 	register.resetForm();
            	// 	showAlert('.alert-msg','Conta criada com sucesso','success');
            	// 	returnButton('Criar','#register button.btn-submit');
            	// } else {
            	// 	showAlert('.alert-msg',response,'error');
            	// 	returnButton('Criar','#register button.btn-submit');
            	// }
            },
            error: function(){
            	returnButton('Criar','#register button.btn-submit');
            }
        })
    }
});

function showCreateAccount() {
	$('#listAccounts').fadeOut(150);
	$('#register').delay(150).fadeIn(150);
    hideAlert('.alert-msg');
}

function hideAlert(alert) {
	$(alert).fadeOut(150);
}

function showAlert(alert,msg,type) {
	$(alert).removeClass('alert-success').removeClass('alert-danger');
	if(type == 'success') {
		$(alert).addClass('alert-success');
	}
	if(type == 'error') {
		$(alert).addClass('alert-danger');
	}
	$(alert).fadeIn(150).text(msg);
}

function returnButton(text,btn) {
	$(btn).text(text).attr("disabled", false);
}

function loadingButton(btn) {
	$(btn).html('<i class="fas fa-circle-notch fa-spin"></i>').attr("disabled", true);
}

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
    $('#report .content').html('');
    $('#report').fadeOut(150);
	$('#generate').delay(150).fadeIn(150);
}

var load = null;

function blurSave() {
	loadSave(3);
}

function focusSave() {
	$('.loading-save').html('<i class="fas fa-save"></i>');
	if(load != null) {
		clearInterval(load);
	}
}

function loadSave(time_max) {
 	$('.loading-save').html('<i class="fas fa-circle-notch fa-spin"></i>');
	var time = time_max;
	load = setInterval(function(){
		if(time != 0) {
			time --;
			$('.loading-save b').text(time);
		} else {
			saveReport();
			$('.loading-save').html('<i class="fas fa-save"></i>');
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

function previewImage(){
    const file = $('#upload')[0].files[0]
    const fileReader = new FileReader()
    fileReader.onload = function() {
        $('.image-user-preview').show();
        imageUser = fileReader.result;
        $('.image-user-preview img').attr('src', imageUser);
        $('.btn-upload').text('Escolher outra imagem');
    }
    fileReader.readAsDataURL(file)
};

function selectImage() {
    $('.image-user-input input').click();
}

function troggleMenu() {
    if($('.navbar-group .left').hasClass('actived')) {
        $('.navbar-group .left').removeClass('actived');
        $('.hamburguer').removeClass('actived');
    } else {
        $('.hamburguer').addClass('actived');
        $('.navbar-group .left').addClass('actived');
    }
}

function showUsersOffice(val) {
    if(val == 'all') {
        window.location.href = 'admin.php';
    } else {
        window.location.href = 'admin.php?office='+val;
    }
}

function showOfficesConfig() {
    $('#listAccounts').fadeOut(150);
    $('#offices').delay(150).fadeIn(150);
    $('#office-register').delay(150).fadeIn(150);
    $('.offices-list').delay(150).fadeIn(150);
}

function showServicesConfig() {
    $('#listAccounts').fadeOut(150);
    $('#services').delay(150).fadeIn(150);
    $('#service-register').delay(150).fadeIn(150);
    $('.services-list').delay(150).fadeIn(150);
}

function returnAdm() {
    editingService = null;
    editingOffice = null;
    $('#service-edit').fadeOut(150);
    $('#office-edit').fadeOut(150);
    $('#register').fadeOut(150);
    $('#offices').fadeOut(150);
    $('#services').fadeOut(150);
    $('#listAccounts').delay(150).fadeIn(150);
    $('input').val('');
    hideAlert('.alert-msg');
}

function showModal(modal) {
    $('.modal-fullpage#'+modal).css('transform','scale(1)');
    setTimeout(function(){
        $('.modal-fullpage#'+modal).css('background-color','rgba(0,0,0,0.75)');
    },150)
}

function closeModal() {
    $('.modal-fullpage').css('background-color','rgba(0,0,0,0)');
    setTimeout(function(){
        $('.modal-fullpage').css('transform','scale(0)');
    },150)
}

function removeOffice(id) {
    showModal('remove-office');
    var name = $('.card-office-'+id+' .nm_office').text().trim();
    $('#remove-office b').text(name);

    removingOffice = id;
}

function removeService(id) {
    showModal('remove-service');
    var name = $('.card-service-'+id+' .nm_service').text().trim();
    $('#remove-service b').text(name);

    removingService = id;
}

function removeUser(id) {
    showModal('remove-user');
    var name = $('.card-user-'+id+' .nm_user p').text().trim();
    $('#remove-user b').text(name);

    removingUser = id;
}

function confirmRemoveUser() {
    closeModal();
    $.ajax({
        type: 'POST',
        url: document.location.origin + '/relatorios/php/remove.php',
        data: {
            id: removingUser,
            type: 'user'
        },
        success: function(response) {
            if(response == true) {
                $('.card-user-'+removingUser).remove();
                showAlert('.alert-msg','Usuário removido com sucesso','success');
            } else {
                showAlert('.alert-msg',response,'error');
            }
        }
    })
}

function confirmRemoveOffice() {
    closeModal();
    $.ajax({
        type: 'POST',
        url: document.location.origin + '/relatorios/php/remove.php',
        data: {
            id: removingOffice,
            type: 'office'
        },
        success: function(response) {
            if(response == true) {
                $('.card-office-'+removingOffice).remove();
                showAlert('.alert-msg','Cargo removido com sucesso','success');
            } else {
                showAlert('.alert-msg',response,'error');
            }
        }
    })
}

function confirmRemoveService() {
    closeModal();
    $.ajax({
        type: 'POST',
        url: document.location.origin + '/relatorios/php/remove.php',
        data: {
            id: removingService,
            type: 'service'
        },
        success: function(response) {
            if(response == true) {
                $('.card-service-'+removingService).remove();
                showAlert('.alert-msg','Cliente removido com sucesso','success');
            } else {
                showAlert('.alert-msg',removingService,'error');
            }
        }
    })
}

function editService(id) {
    hideAlert('.alert-msg');
    var name = $('.services-list .card-service-'+id+' .nm_service').text().trim();
    $('#service-register').fadeOut(150);
    $('.services-list').fadeOut(150);
    $('#service-edit').delay(150).fadeIn(150);

    $('#service-edit input[type="text"]').val(name);
    $('#service-edit input[type="hidden"]').val(id);
}

function editOffice(id) {
    hideAlert('.alert-msg');
    var name = $('.offices-list .card-office-'+id+' .nm_office').text().trim();
    $('#office-register').fadeOut(150);
    $('.offices-list').fadeOut(150);
    $('#office-edit').delay(150).fadeIn(150);

    $('#office-edit input[type="text"]').val(name);
    $('#office-edit input[type="hidden"]').val(id);
}

function cancelEdit(type) {
    hideAlert('.alert-msg');
    editingService = null;
    editingOffice = null;
    $('#service-edit').fadeOut(150);
    $('#office-edit').fadeOut(150);
    $('#'+type+'-register').delay(150).fadeIn(150);
    $('.'+type+'s-list').delay(150).fadeIn(150);
}