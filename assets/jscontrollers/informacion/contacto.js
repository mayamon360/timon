$( document ).ready(function() {
    $('#form_send')[0].reset();
})

// RECAPTCHA GOOGLE
var onloadCallback=function(){grecaptcha.render('g-recaptcha',{'sitekey':'6Ld3NcwUAAAAAD-UOj3RgOrs-FA7icxGJD8bMeMo'});};
// RECAPTCHA GOOGLE

//grecaptcha.reset();

function formSend(){
    var $appForm = $(this), __data = {};
    $('#form_send').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/contactMessage',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
                $('#btn_send .spinner-grow').removeClass('d-none');
                $('#btn_send').attr('disabled','disabled');
            },
            success : function(json) {
                if(json.status == 'success') {
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: json.status,
                        closeOnClickOutside: false,
                        closeOnEsc: false,
                        buttons: {
                            confirm: {
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
                            location.reload();
                        }
                    })
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
                            $('#btn_send').removeAttr('disabled');
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
                $('#btn_send .spinner-grow').addClass('d-none');
            } 
        });
    }
}

$('#btn_send').click(function(e) {
    e.defaultPrevented;
    formSend();
});
$(document).on('input', 'form#form_send', function() {
    $('#btn_send').removeAttr('disabled');
});
$('form#form_send').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        formSend();
        return false;
    }
});