$(document).ready(function(){
    /*
    var shop_cart = 'full';
    function check_cart(){
        $.ajax({
            type : "POST",
            url : "api/checkCart",
            success: function(json){
                if(json == 'reload'){
                    shop_cart = 'empty';
                    location.reload();
                }else if(json == 'change_stock'){
                    window.location.href = "./compra?error=cambio_stock";
                }
            }
        })
    }
    var count_interval = setInterval(function(){
        check_cart();
        if(shop_cart == 'empty'){
            clearInterval(count_interval);
        }
    },5000);
    */
})

function cargarCompra(){
    $.ajax({
        type : 'GET',
        url : 'api/cargarCompraDos',
        dataType: 'json',
        success : function(json) {
            $('.resultados').html(json.resultados);
            $('.tabla_carrito').html(json.content);
            $('.subtotal').html(json.subtotal);
            $('.envio').html(json.envio);
            $('.total').html(json.total);
            $('.total_boton').html(json.total);
            $('.cantidad_carrito').html(json.cantidad);
            if(json.status == 'empty'){
                $('.tabla_carrito').removeClass('table-hover');
                $('.div_procesar_pago').addClass('d-none');
            }else if(json.status == 'full'){
                $('.tabla_carrito').addClass('table-hover');
                $('.div_procesar_pago').removeClass('d-none');
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
        }
    })
}
cargarCompra();