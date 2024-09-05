// -----------------------------------------------------------------------------
$(function(){
    
    $('[data-toggle="tooltip"]').tooltip();
    
    // AGREGAR PRODUCTO
    // Activa select 2, para categoria, subcategoria y editorial
    $('.seleccionarEditorial').select2({
        width: "resolve"
    })
    // Activa select 2, para autores 
    $(".seleccionarAutores").select2({
        placeholder: "Autor(es)"
    })
    // Al estar en select de autores o de editorial se ocultan los divs para agregar autor y editorial
    $(".seleccionarAutores, .seleccionarEditorial").on("select2:open", function (e) {
        $(".divAgregarAutor").addClass('hidden');
        $(".divAgregarEditorial").addClass('hidden');
    });
    $(".select2-search__field").css("width", "100%");
    
});
// -----------------------------------------------------------------------------

cargarListaPedido();
limpiarFormulario();

function cargarListaPedido(){
    $.ajax({
        type : "POST",
        url : "api/cargarListaPedido",
        dataType: 'json',
        success:function(json){
            if(json.status == 'llena'){
                $("#nuevoPedido").removeClass('hidden');
            }else{
                $("#nuevoPedido").addClass('hidden');
            }
            $("#listaPedido tbody").html(json.tbody);
            $("#folio_pedido").html(json.folio_general);
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        }
    })
}

$(".cantidadNuevo").inputmask("9{1,3}",{ 
    "placeholder": "0",
    "rightAlign": false,
    "oncleared": function(){ 
        $(this).val('');  
    }
});
$(document).on('input', '.cantidadNuevo', function(){
    
    var cantidad = $(this).val();

    if(cantidad === '' || cantidad === 0){
        cantidad = 1;
    }
    cantidad = parseInt(cantidad);
    
    var precio = Number( $('#precio').val() );
    if(precio !== '' && precio !== 0){
        
        $('#anticipo').val( (cantidad * precio) / 2  );
        $("#total").val( cantidad * precio );
        
    }else{
        
        $('#anticipo').val('');
        $("#total").val('');
        
    }
    
});

$("#precioCompra, #precio, #anticipo, #total").inputmask({ 
    alias: "decimal",
    integerDigits: 5,
    digits: 2,
    digitsOptional: false,
    allowPlus: false,
    allowMinus: false,
    placeholder: "0",
    "oncleared": function(){ 
        $(this).val('');
    }
});

$(document).on('input', '#precio', function(){
    var cantidad = $(".cantidadNuevo").val();
    if(cantidad === '' || cantidad === 0){
        cantidad = 1;
    }
    cantidad = parseInt(cantidad);
    
    var precio = $(this).val();
    var anticipo = Number( (cantidad * precio) / 2 );
    $("#anticipo").val( anticipo );
    var total = Number ( cantidad * precio );
    $("#total").val(cantidad*precio);
});

// Función para obtener las editoriales
function obtenerEditoriales(seleccionado = 0, modal = 'agregar') {
    var metodo = 'cargar';
    $.ajax({
        type: "POST",
        url: "api/obtenerEditoriales",
        dataType: 'json',
        data: { metodo: metodo, seleccionado: seleccionado },
        success: function (json) {
            if(modal == 'agregar'){
                $(".seleccionarEditorial").html(json.editoriales);
            }else if(modal == 'editar'){
                $(".eSeleccionarEditorial").html(json.editoriales);
            }
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function () {
            //Initialize Select2 Elements
            $('#editorial').select2();
            $('#eEditorial').select2();
        }
    });
}
    
// Función para mostrar y ocultar div para agregar editorial en el modal agregar
$('.agregarEditorial').on('click', function (e) {
    $(".divAgregarEditorial").toggleClass('hidden');
    $(".divAgregarAutor").addClass('hidden');
    $("#nuevaEditorial").focus();
})

// Función para registrar nueva editorial en el modal agregar
$('#registrarEditorial').on('click', function (e) {
    var editorial = $("#nuevaEditorial").val();
    $.ajax({
        type: "POST",
        url: "api/agregarEditoriales",
        dataType: 'json',
        data: { nombreE: editorial },
        beforeSend: function () {
            $("#registrarEditorial").attr("disabled", true);
            $("#enviar").attr("disabled", true);
        },
        success: function (json) {
            if (json.status == 'success') {
                $(".divAgregarEditorial").toggleClass('hidden');
                obtenerEditoriales(json.agregado);
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
                            className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                            closeModal: true,
                        }
                    }
                });
            }
            $("#nuevaEditorial").val("");
            setTimeout(function(){ 
                $("#enviar").removeAttr("disabled");
            }, 800);
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function () {
            $("#registrarEditorial").removeAttr("disabled");
        }
    })
})
    
// Función para obtener los autores
function obtenerAutores(seleccionado = 0, modal = 'agregar') {
    var metodo = 'cargar';
    var autoresSeleccionados = '';
    if(modal == 'agregar'){
        autoresSeleccionados = $("#autores").val();
    }else if(modal === 'editar'){
        autoresSeleccionados = $("#eAutores").val();
    }

    $.ajax({
        type: "POST",
        url: "api/obtenerAutores",
        dataType: 'json',
        data: { metodo: metodo, seleccionado: seleccionado, autoresSeleccionados: autoresSeleccionados },
        success: function (json) {
            if(modal == 'agregar'){
                $(".seleccionarAutores").html(json.autores);
            }else if(modal == 'editar'){
                $("#eAutores").html('');
                $("#eAutores").html(json.autores);
            }
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function () {
            //Initialize Select2 Elements
            $("#autores").select2();
            $('#eAutores').select2();
        }
    });
}
    
// Función para mostrar y ocultar div para agregar autor en el modal agregar
$('.agregarAutor').on('click', function (e) {
    $(".divAgregarAutor").toggleClass('hidden');
    $(".divAgregarEditorial").addClass('hidden');
    $("#nuevoAutor").focus();
})
// Función para registrar nuevo autor en el modal agregar
$('#registrarAutor').on('click', function (e) {
    var autor = $("#nuevoAutor").val();
    
    $.ajax({
        type: "POST",
        url: "api/agregarAutores",
        dataType: 'json',
        data: { nombreA: autor },
        beforeSend: function () {
            $("#registrarAutor").attr("disabled", true);
            $("#enviar").attr("disabled", true);
        },
        success: function (json) {
            if (json.status == 'success') {
                $(".divAgregarAutor").toggleClass('hidden');
                obtenerAutores(json.agregado);
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
                            className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                            closeModal: true,
                        }
                    }
                });
            }
            $("#nuevoAutor").val("");
            setTimeout(function(){ 
                $("#enviar").removeAttr("disabled");
            }, 800);
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function () {
            $("#registrarAutor").removeAttr("disabled");
        }
    })
});

$(document).on('click','#enviar',function(e){
    swal({
        title: "!Atención¡",
        text: "¿Estás seguro de agregar al pedido?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, solicitar",
                value: true,
                visible: true,
                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-danger",
                closeModal: true,
            },
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-default",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if (value) {
            agregarProducto();
        }
    })
});

function agregarProducto() {
    
    var formData = new FormData();
    
    formData.append("id_producto", $("#id_producto").val());
    formData.append("cantidadNuevo", $("#cantidadNuevo").val());
    formData.append("codigoP", $("#codigoP").val());
    formData.append("nombreP", $("#nombreP").val());
    formData.append("leyenda", $("#leyenda").val());
    formData.append("editorial", $("#editorial").val());
    formData.append("autores", $("#autores").val());
    formData.append("precioCompra", $("#precioCompra").val());
    formData.append("precio", $("#precio").val());
    formData.append("anticipo", $("#anticipo").val());
    formData.append("metodoPago", $("#metodoPago").val());
    formData.append("a_nombre", $("#a_nombre").val());

    $.ajax({
        type : "POST",
        url : "api/agregarPedido",
        dataType: 'json',
        data : formData,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){ 
            $("#enviar").attr("disabled", true);
        },
        success : function(json) {
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
                            className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                            closeModal: true,
                        }
                    }
                });
            }else{
                limpiarFormulario();
                $.notify({
                    icon: 'glyphicon glyphicon-warning-sign',
                    title: '<strong>' + json.title + '</strong>',
                    message: json.message,
                }, {
                    position: 'fixed',
                    type: 'success',
                    placement: {
                        from: "bottom",
                        align: "center"
                    },
                    z_index: 1000,
                    delay: 1000,
                    timer: 500,
                    mouse_over: "pause",
                    animate: {
                        enter: 'animated bounceIn',
                        exit: 'animated bounceOut'
                    }
                }); 
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            $("#enviar").removeAttr("disabled");
            cargarListaPedido();
        } 
    })

}

$(document).on('click','#nuevoPedido',function(e){
    
    swal({
        title: "!Atención¡",
        text: "¿Estás seguro de crear un nuevo pedido?",
        icon: 'warning',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: {
            confirm: {
                text: "Sí, crear nuevo",
                value: true,
                visible: true,
                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-danger",
                closeModal: true,
            },
            cancel: {
                text: "Cancelar",
                value: null,
                visible: true,
                className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-default",
                closeModal: true,
            }
        }
    })
    .then((value) => {
        if (value) {

            $.ajax({
                type : "POST",
                url : "api/nuevoPedido",
                dataType: 'json',
                beforeSend: function(){ 
                    $("#nuevoPedido").attr("disabled", true);
                },
                success : function(json) {
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
                                    className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                                    closeModal: true,
                                }
                            }
                        });
                    }else{
                        limpiarFormulario();
                        $.notify({
                            icon: 'glyphicon glyphicon-warning-sign',
                            title: '<strong>' + json.title + '</strong>',
                            message: json.message,
                        }, {
                            position: 'fixed',
                            type: 'success',
                            placement: {
                                from: "bottom",
                                align: "center"
                            },
                            z_index: 1000,
                            delay: 1000,
                            timer: 500,
                            mouse_over: "pause",
                            animate: {
                                enter: 'animated bounceIn',
                                exit: 'animated bounceOut'
                            }
                        });
                    }
                },
                error : function(xhr, status) {
                    alert('Ha ocurrido un problema interno');
                },
                complete: function(){ 
                    $("#nuevoPedido").removeAttr("disabled");
                    cargarListaPedido();
                }
            })
        }
    })
});

/* Limpiar el formulario del modal agregar */
$('#limpiar').on('click', function (e) {
    limpiarFormulario();
});
function limpiarFormulario(){
    
    $("#form_productoPedido")[0].reset();
    
    $('#id_producto').val('');
    
    $('.seleccionarAutores').val(null).trigger('change');
    $("#nuevoAutor").val("");
    $('.seleccionarEditorial').val(null).trigger('change');
    $("#nuevaEditorial").val("");
    
    $('#id_producto').removeAttr('readonly');
    $('#codigoP').removeAttr('readonly');
    $('#nombreP').removeAttr('readonly');
    $('#leyenda').removeAttr('readonly');
    
    $('#editorial_inputs').removeClass('hidden');
    $('#editorial_text').addClass('hidden');
    $('#editorial_BD').val('');
    
    $('#autor_inputs').removeClass('hidden');
    $('#autor_text').addClass('hidden');
    $('#autor_BD').val('');
    
    $('#precioCompra').removeAttr('readonly');
    $('#precio').removeAttr('readonly');
    
    $('#a_nombre').val('');
    
    obtenerEditoriales();
    obtenerAutores();
}

$('#modalProductos').on('shown.bs.modal', function (e) {

    if ( ! $.fn.DataTable.isDataTable( '#tablaProductosVer' ) ) {

        $('#tablaProductosVer thead tr').clone(true).appendTo( '#tablaProductosVer thead' ).addClass("bg-gray");
        $('#tablaProductosVer thead tr:eq(1) th').each( function (i) {
            if(i == 3 || i == 4){
                var title = $(this).text();
                $(this).html( '<input class="form-control input-sm" type="text" placeholder="Filtrar por '+title+'" style="max-width:170px;"/>' );
                $( 'input', this ).on( 'keyup change', function () {
                    if ( dataTable.column(i).search() !== this.value ) {
                        dataTable
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            }else{
                $(this).html( '' );
            }
        } );
    }else{
        $('#tablaProductosVer').DataTable().ajax.reload();
    }

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaProductosVer').DataTable({
        //"responsive": true,
        "scrollX": true,
        "order": [],   
        "orderCellsTop": true, 
        "ajax": {
            url: "api/mostrarProductosPedidos",
            type: "POST",
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "iDisplayLength" : 10, 
        "retrieve" : true,
        "deferRender": true,
        "processing" : true,
        "searchHighlight": true,
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
            },
            {
                "targets": [5,6],
                "className": "text-center font-weight-bold",
            },
            {
                "targets": 7,
                "orderable": false,
            }],
        "language": {
            "decimal": "",
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando lista de productos...",
            "processing": "Procesando...",
            "search": "BUSCAR PRODUCTO:",
            "zeroRecords": "No se encontrarón resultados",
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": ">",
                "previous": "<"
            },
            "aria": {
                "sortAscending": ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        }
    });
    /* /Cargar los datos en la tabla */

    $('#tablaProductosVer_filter input[type="search"]').focus();
})

$(document).on('click','#btnAgregarPedido',function(e){
    var key = $(this).attr("key");
    $.ajax({
        type : "POST",
        url : "api/productoPedidoFormulario",
        dataType: 'json',
        data : {key:key},
        success : function(json) {
            if(json.status == 'success'){
                
                $('#modalProductos').modal('hide');
                
                $('#cantidadNuevo').val(1);
                
                $('#id_producto').val(json.id).attr('readonly','readonly');
                $('#codigoP').val(json.codigo).attr('readonly','readonly');
                $('#nombreP').val(json.producto).attr('readonly','readonly');
                $('#leyenda').val(json.leyenda).attr('readonly','readonly');
                
                $('#editorial_inputs').addClass('hidden');
                $('#editorial_text').removeClass('hidden');
                $('#editorial_BD').val(json.editorial);
                
                $('#autor_inputs').addClass('hidden');
                $('#autor_text').removeClass('hidden');
                $('#autor_BD').val(json.autores);
                
                $('#precioCompra').val(json.costo).attr('readonly','readonly');
                $('#precio').val(json.precio).attr('readonly','readonly');
                
                $('#total').val(json.precio);
                $('#anticipo').val(json.anticipo);
                
            }else{
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
                            className: "btn btn-sm btn-flat font-weight-bold text-uppercase btn-primary",
                            closeModal: true,
                        }
                    }
                });
            }
        },
        error : function(xhr, status) {
            console.log('Ha ocurrido un problema interno');
        }
    })
})

$(document).on('click', '#opciones_aperturaCaja', function(e){
    $.ajax({
        type : "POST",
        url : "api/obtenerFormularioCaja",
        dataType: 'json',
        success : function(json) {
            $("#registrar_monto_form").html(json.formulario);
        },
        complete: function(){
            $("#input_monto_inicial").number(true,2);
        }
    })
})

function registrarMontoInicial(){
    var $ocrendForm = $(this), __data = {};
    $('#registrar_monto_form').serializeArray().map(function(x){__data[x.name] = x.value;}); 

    if(undefined == $ocrendForm.data('locked') || false == $ocrendForm.data('locked')) {
        $.ajax({
            type : "POST",
            url : "api/registrarMontoInicial",
            dataType: 'json',
            data : __data,
            beforeSend: function(){ 
                $ocrendForm.data('locked', true);
                $("button#registrar_monto").attr("disabled", true);
            },
            success : function(json) {
                if(json.status == 'success') {
                    $('#modalRegistrarMonto').modal('hide');
                    swal({
                        title: json.title,
                        text: json.message,
                        icon: "success",
                        closeOnClickOutside: false,
                        closeOnEsc: true,
                        buttons: {
                            confirm: {
                                text: "Cerrar",
                                value: true,
                                visible: true,
                                className: "btn btn-sm btn-primary",
                                closeModal: true,
                            }
                        }
                    })
                    .then((value) => {
                        location.reload();
                    })
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
                                className: "btn btn-sm btn-primary",
                                closeModal: true,
                            }
                        }
                    });
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                $ocrendForm.data('locked', false);
                $("button#registrar_monto").removeAttr("disabled");
            } 
        });
    }
}
$('button#registrar_monto').click(function(e) {
    e.defaultPrevented;
    registrarMontoInicial();
});
