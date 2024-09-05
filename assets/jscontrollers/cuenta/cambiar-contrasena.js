$('#n_password').keyup(function(e) {
    var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{6,}).*", "g");
    if (false == enoughRegex.test($(this).val())) {
            $('.progress-bar').addClass('bg-red').removeClass('bg-green bg-yellow');
            $('.progress-bar').attr({'aria-valuenow' : 0,}).css('width', '0%');
            $('.progress-label').html('Mínimo 6 caracteres');
    } else if (strongRegex.test($(this).val())) {
            $('.progress-bar').addClass('bg-green').removeClass('bg-yellow bg-red');
            $('.progress-bar').attr({'aria-valuenow' : 100,}).css('width', '100%');
            $('.progress-label').html('¡Contraseña fuerte!');
    } else if (mediumRegex.test($(this).val())) {
            $('.progress-bar').addClass('bg-yellow').removeClass('bg-red bg-green');
            $('.progress-bar').attr({'aria-valuenow' : 66.66,}).css('width', '66.66%');
            $('.progress-label').html('¡Contraseña media!');
    } else {
            $('.progress-bar').addClass('bg-red').removeClass('bg-yellow bg-green');
            $('.progress-bar').attr({'aria-valuenow' : 33.33,}).css('width', '33.33%');
            $('.progress-label').html('¡Contraseña debil!');
    }
});
$('#mostrar_contraseña').click(function(){
    $(this).addClass('d-none');
    $('#ocultar_contraseña').removeClass('d-none');
    $('input[name="n_password"]').prop('type', 'text');
});
$('#ocultar_contraseña').click(function(){
    $(this).addClass('d-none');
    $('#mostrar_contraseña').removeClass('d-none');
    $('input[name="n_password"]').prop('type', 'password');
});

function changePassword(){
    var $appForm = $(this), __data = {};
    $('#form_change_password').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/changePassword',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
            },
            success : function(json) {
                if(json.status == 'warning'){
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'warning',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: false,
                        timer: 800
                    });
                    setTimeout(function() {
                        window.location = 'autenticacion';
                    }, 800);
                }else if(json.status == 'success'){
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'success',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: true,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                    .then((value) => {
                        if(value){
                            $('#form_change_password')[0].reset();
                            $('#btn_change_password').attr('disabled', 'disabled');
                            $('.progress-bar').addClass('bg-red').removeClass('bg-green bg-yellow');
                            $('.progress-bar').attr({'aria-valuenow' : 0,}).css('width', '0%');
                            $('.progress-label').html('Análisis de seguridad');
                        }
                    })
                }else if(json.status == 'error'){
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'error',
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            cancel: {
                                text: 'CERRAR',
                                value: true,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                }
            },
            error : function(xhr, status) {
                swal({
                    title: '¡ERROR INTERNO!',
                    text: 'Para más detalles, contacte al administrador.',
                    icon: 'error',
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                    buttons: {
                        cancel: {
                            text: 'CERRAR',
                            value: null,
                            visible: true,
                            className: 'btn btn-sm boton_negro',
                            closeModal: true,
                        }
                    }
                })
            },
            complete: function(){ 
                $appForm.data('locked', false);
            } 
        });
    }
} 
$('#btn_change_password').click(function(e) {
    e.defaultPrevented;
    changePassword();
});
$('form#form_change_password input').keypress(function(e) {
    e.defaultPrevented;
    $('#btn_change_password').removeAttr('disabled');
    if(e.which == 13) {
        changePassword();
        return false;
    }
});