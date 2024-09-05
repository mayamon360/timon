/* JS LOGIN */

// DOM
$(function(){$('.seleccionarPerfil').select2();});
// DOM

// RECAPTCHA GOOGLE
var onloadCallback=function(){grecaptcha.render('g-recaptcha',{'sitekey':'6Ld3NcwUAAAAAD-UOj3RgOrs-FA7icxGJD8bMeMo'});};
// RECAPTCHA GOOGLE

// VALIDAR CORREO
$(document).on('change', '.validar', function(){
    var valor = $(this).val();
    $.ajax({
        url:"api/validarCorreoAdmin",
        method:'POST',
        dataType: 'json',
        data : {valor:valor},
        beforeSend: function(){$(".loader").toggleClass('hidden');},
        success:function(json){
            if(json.status == 'error'){
                swal({
                    title: json.title,
                    text: json.message,
                    icon: "error",
                    closeOnClickOutside: false,
                    closeOnEsc: true,
                    buttons: {
                        confirm: {
                            text: "Cerrar",
                            value: true,
                            visible: true,
                            className: "btn btn-sm btn-flat btn-primary text-uppercase font-weight-bold",
                            closeModal: true,
                         }
                    }
                })
                .then((value) => {
                    if(value){
                        $(".validar").val("");
                        $(".validar").focus();
                        $("#login").attr('disabled','disabled');
                        $("#perfiles").html('');
                        $("#almacenes").html('');
                    }
                });
            }
            if(json.status == 'success'){
                grecaptcha.reset();
                $("#perfiles").html(json.perfiles);
                if(json.almacenes != ''){
                    $("#almacenes").html(json.almacenes);
                    if(json.pap === true){
                        $("#almacenes").removeClass('hidden');
                    }
                }
                $("#login").removeAttr('disabled');
                var loginBoxHeight = $(".login-box").height();
                loginBoxHeight = loginBoxHeight/2;
                loginBoxHeight = "-"+loginBoxHeight+"px !important";
                $(".login-box").attr('style', 'margin-top: '+loginBoxHeight);
            }
        },
        error : function(xhr, status) {
            alert('Problema interno al intentar validar el correo electrónico.');
        }
    })
});
// VALIDAR CORREO

// INICIAR SESIÓN
function login(){
    var $ocrendForm = $(this), __data = {};
    $('#login_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 
    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/login",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true) ;
                $("#login").attr("disabled", true);
            },
            success : function(json) {
                if(json.success == 1) {
                    location.reload();
                } else {
                    
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "error",
                        closeOnClickOutside: false,
                        closeOnEsc: true,
                        buttons: {
                            confirm: {
                                text: "Cerrar",
                                value: true,
                                visible: true,
                                className: "btn btn-sm btn-flat btn-primary text-uppercase font-weight-bold",
                                closeModal: true,
                             }
                        }
                    })
                    .then((value) => {
                        if(value){

                            grecaptcha.reset();
                            
                            $("#login_form")[0].reset();
                            $(".validar").focus();
                            $("#login").attr('disabled','disabled');
                            $("#perfiles").html('');
                            $("#almacenes").html('');
                        }

                    });

                    $("#login").removeAttr("disabled");

                }
            },
            error : function(xhr, status) {
                alert('Problema interno al intentar iniciar sesión.');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
            } 
        });
    }
}
// INICIAR SESIÓN

/**
 * EVENTOS
 */
$('#login').click(function(e) {
    e.defaultPrevented;
    login();
});
$('form#login_form input.pass').keypress(function(e) {
    e.defaultPrevented;
    if(e.which == 13) {
        login();
        return false;
    }
});
$(".logOut").click(function(e){
    e.defaultPrevented;
    window.location.replace('login');
})
$(document).on('change', '.seleccionarPerfil', function(){
    var perfil = $(this).val();
    if(perfil == 4){
        $("#almacenes").removeClass('hidden');
        var loginBoxHeight = $(".login-box").height();
        loginBoxHeight = loginBoxHeight/2;
        loginBoxHeight = "-"+loginBoxHeight+"px !important";
        $(".login-box").attr('style', 'margin-top: '+loginBoxHeight);
    }else{
        $("#almacenes").addClass('hidden');
    }
})

/* JS LOGIN */