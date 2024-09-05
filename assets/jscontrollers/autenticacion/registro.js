$( document ).ready(function() {
    $('#form_register')[0].reset();
})

function register(){
    
    var r_name = $("#r_name").val();
    var r_mail = $("#r_mail").val();
    var r_password = $("#r_password").val();
    var r_repassword = $("#r_repassword").val();
    
    $.ajax({
        type : 'POST',
        url : 'api/register',
        dataType: 'json',
        data: {r_name:r_name,r_mail:r_mail,r_password:r_password,r_repassword:r_repassword},
        beforeSend: function(){
            $('#btn_register .spinner-grow').removeClass('d-none');
            $('#btn_register').attr('disabled',true);
        },
        success : function(json) {
            if(json.status == 'success') {
                $('#form_register')[0].reset();
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
                        $('#form_register')[0].reset();
                        $('#btn_register').removeAttr('disabled');
                        $(location).attr('href', 'https://eltimonlibreria.com/autenticacion');
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
                        $('#btn_register').removeAttr('disabled');
                    }
                })
            }
        },
        error : function(xhr, status) {
            $('#btn_register').removeAttr('disabled');
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
            $('#btn_register .spinner-grow').addClass('d-none');
        } 
    });
} 
$('#btn_register').click(function(e) {
    e.defaultPrevented;
    register();
});
$('form#form_register input').keypress(function(e) {
    e.defaultPrevented;
    $('#btn_register').removeAttr('disabled');
    if(e.which == 13) {
        register();
        return false;
    }
});
