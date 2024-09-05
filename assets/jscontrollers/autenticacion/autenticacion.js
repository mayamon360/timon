$( document ).ready(function() {
    $('#form_login')[0].reset();
})

// RECAPTCHA GOOGLE
var onloadCallback=function(){
    grecaptcha.render('g-recaptcha',
    {
        'sitekey':'6Ld3NcwUAAAAAD-UOj3RgOrs-FA7icxGJD8bMeMo'
    })
}
// RECAPTCHA GOOGLE

$('#mostrar_contraseña').click(function(){
    $(this).addClass('d-none');
    $('#ocultar_contraseña').removeClass('d-none');
    $('input[name="l_password"]').prop('type', 'text');
    $("#l_password").focus();
});
$('#ocultar_contraseña').click(function(){
    $(this).addClass('d-none');
    $('#mostrar_contraseña').removeClass('d-none');
    $('input[name="l_password"]').prop('type', 'password');
    $("#l_password").focus();
});

function login(){
    var $appForm = $(this), __data = {};
    $('#form_login').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/login',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
                $('#btn_login .spinner-grow').removeClass('d-none');
                $('#btn_login').attr('disabled','disabled');
            },
            success : function(json) {
                if(json.status == 'success' || json.status == 'info') {
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: json.status,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: false,
                        timer: 1000
                    });
                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
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
                    .then((value) => {
                        if(value){
                            grecaptcha.reset();
                            $('#btn_login').removeAttr('disabled');
                        }
                    })
                }
            },
            error : function(xhr, status) {
                grecaptcha.reset();
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
                $('#btn_login .spinner-grow').addClass('d-none');
            } 
        });
    }
} 
$('#btn_login').click(function(e) {
    e.defaultPrevented;
    login();
});
$(document).on('input', 'form#form_login input', function() {
    $('#btn_login').removeAttr('disabled');
});
$('form#form_login input').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        login();
        return false;
    }
});