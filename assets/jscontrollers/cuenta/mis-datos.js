$(document).ready(function() {
    $(".d_rfc").inputmask({"mask": "A{3,4}-999999-***"});
    $(".d_phone").inputmask({"mask": "9{10}"});
    $(".p_code").inputmask({"mask": "99999"});
    $(".o_number").inputmask({"mask": "9{1,5}"});
    $('.selectpicker')[0] && $('.selectpicker').selectpicker();
})


function saveData(){
    var $appForm = $(this), __data = {};
    $('#form_data').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/saveData',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
                $('#btn_data .spinner-grow').removeClass('d-none');
                $('#btn_data').attr('disabled','disabled');
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
                }else if(json.status == 'success') {
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'success',
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
                } else if(json.status == 'error') {
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
                            $('#btn_data').removeAttr('disabled');
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
                $('#btn_data .spinner-grow').addClass('d-none');
                loadData();
            } 
        });
    }
} 
$('#btn_data').click(function(e) {
    e.defaultPrevented;
    saveData();
});
$('form#form_data input').on('input', function(e){
    $('#btn_data').removeAttr('disabled');
});
$('form#form_data input').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        saveData();
        return false;
    }
});








function saveAddress(){
    var $appForm = $(this), __data = {};
    $('#form_address').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $appForm.data('locked') || false == $appForm.data('locked')) {
        $.ajax({
            type : 'POST',
            url : 'api/saveAddress',
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $appForm.data('locked', true);
                $('#btn_address .spinner-grow').removeClass('d-none');
                $('#btn_address').attr('disabled','disabled');
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
                }else if(json.status == 'success') {
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: 'success',
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
                } else if(json.status == 'error') {
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
                            $('#btn_address').removeAttr('disabled');
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
                $('#btn_address .spinner-grow').addClass('d-none');
                loadData();
            } 
        });
    }
} 
$('#btn_address').click(function(e) {
    e.defaultPrevented;
    saveAddress();
});
$('form#form_address input, form#form_address textarea').on('input', function(e){
    $('#btn_address').removeAttr('disabled');
});
$('.state').on('changed.bs.select', function () {
    $('#btn_address').removeAttr('disabled');
});

$('form#form_address input').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        saveAddress();
        return false;
    }
});







function loadData(){
    $.ajax({
        type : 'GET',
        url : 'api/loadData',
        dataType: 'json',
        beforeSend: function(){ 
            $('.div_loading_data').addClass('d-flex justify-content-center').removeClass('d-none');
            $('.div_data_inputs').addClass('d-none');
        },
        success : function(json) {
            $('.d_mail').val(json.email);
            $('.d_name').val(json.name);
            $('.d_rfc').val(json.rfc);
            $('.d_phone').val(json.phone);
            $('.user_letter').html(json.name.slice(0,1));
            $('.user_name').html(json.name);
            
            $('.p_code').val(json.p_code);
            $('.state').selectpicker('val', json.state);
            $('.municipality').val(json.municipality);
            $('.colony').val(json.colony);
            $('.street').val(json.street);
            $('.o_number').val(json.o_number);
            $('.b_streets').val(json.b_streets);
            $('.a_references').val(json.a_references);
            
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
            $('.div_loading_data').removeClass('d-flex justify-content-center').addClass('d-none');
            $('.div_data_inputs').removeClass('d-none');
        } 
    })
}
loadData();