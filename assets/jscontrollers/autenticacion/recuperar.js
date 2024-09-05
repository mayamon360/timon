$( document ).ready(function() {
    $('#form_forgot')[0].reset();
})

function forgot(){
    var $appForm = $(this), __data = {};
    $('#form_forgot').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/lostpass',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
                $('#btn_forgot .spinner-grow').removeClass('d-none');
                $('#btn_forgot').attr('disabled','disabled');
            },
            success : function(json) {
                if(json.status == 'success') {
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
                            $('#form_forgot')[0].reset();
                            $('#btn_forgot').attr('disabled', 'disabled');
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
                                value: null,
                                visible: true,
                                className: 'btn btn-sm boton_negro',
                                closeModal: true,
                            }
                        }
                    })
                    .then((value) => {
                        if(value){
                            $('#btn_forgot').removeAttr('disabled');
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
                $('#btn_forgot .spinner-grow').addClass('d-none');
            } 
        });
    }
} 
$('#btn_forgot').click(function(e) {
    e.defaultPrevented;
    forgot();
});
$('form#form_forgot input').keypress(function(e) {
    e.defaultPrevented;
    $('#btn_forgot').removeAttr('disabled');
    if(e.which == 13) {
        forgot();
        return false;
    }
});