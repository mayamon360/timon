$(document).ready(function () {
    
    // GENERAL                          ---------------------------------------------------------------- GENERAL

    // Activa tooltip sirve para los titles del modal agregar
    $('[data-toggle="tooltip"]').tooltip();
    
    // Agrega tfoot a la tabla
    $('#tablaProductosVer thead tr').clone(true).appendTo( '#tablaProductosVer thead' ).addClass('bg-gray');
    
    // Agrega campo filtrar por en la cabecera de las columnas autor y editorial
    $('#tablaProductosVer thead tr:eq(1) th').each( function (i) {
        // muestra campo de busqueda para autor y editorial
        if(i == 3 || i == 4){
            var title = $(this).text();
            var ancho = '170px';
            $(this).html( '<input class="form-control input-sm" type="text" placeholder="Buscar" style="max-width:'+ancho+'; font-weight:normal; padding: 3px;"/>' );
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
    })
    
    // Carga los datos en la tabla
    var dataTable = $('#tablaProductosVer').DataTable({
        "pagingType": "input",
        "scrollX": true,
        "order": [],   
        "orderCellsTop": true, 
        "ajax": {
            url: "api/mostrarProductos",    // Endpoint Productos
            type: "POST",
            "complete": function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        },
        "lengthMenu": [[15, 30], [15, 30]],
        "iDisplayLength" : 15, 
        "retrieve" : true,
        "deferRender": true,
        "processing" : true,
        "searchHighlight": true,
        "columnDefs": [
            {
                "targets": 0,                                   // codigo
                "orderable": false,
            },
            {
                "targets": 1,                                   // id
                "className": "text-center",
            },
            {
                "targets": 5,                                 // precio
                "className": "text-right font-weight-bold cursor-pointer",
            },
            {
                "targets": 6,                                   // stock
                "className": "text-center cursor-pointer",
            },
            {
                "targets": [7],                                 // monto
                "className": "text-right",
            },
            {
                "targets": [8],                                 // ventas
                "className": "text-center",
            },
            {
                "targets": 9,
                "className": "text-center",
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                    
                    // row1  = id_producto
                    // row10 = stock oculto sin formato
                    // row11 = estado (0,1)
                    // row14 = control para el boton eliminar (SI,NO)
                    
                    // Inicializar botones
                    var btnAgregarVenta = "";
                    var btnEliminar = "";
                    
                    // Si el stock es mayor a 0 y ademas el estado es igual a 1(activo)
                    if(row['10'] > 0 && row['11'] == 1){
                        btnAgregarVenta = "<i class='fas fa-share text-aqua' id='btnAgregarVenta' key='"+row[1]+"' style='cursor:pointer; margin-left:15px;' data-toggle='tooltip' title='Agregar a la lista de salida' data-placement='left'></i>";
                    }
                    
                    // Si se puede eliminar
                    if(row['14'] == 'SI'){
                        btnEliminar = "<i class='fas fa-trash-alt text-red eliminar' key='"+row[1]+"' style='cursor:pointer;'></i>";
                    }
                    
                    return "<i class='fas fa-pencil-alt text-blue obtenerProducto' key='"+row[1]+"' data-toggle='modal' data-target='#modalEditar' style='cursor:pointer; margin-right:5px;'></i> " + btnEliminar + ' ' + btnAgregarVenta;
                }
            },
            {
                "targets": [10,11,14],
                "visible": false,
                "searchable": false
            },
            {
                "targets": [12,13],
                "visible": false
            }
        ],
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
        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;
            // Eliminar el formato para obtener datos enteros para sumar
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total de todas las páginas
            total = api
                .column(7)                              // columna 7 = monto
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
    
            // Total en esta página
            pageTotal = api
                .column(7, { page: 'current' })         // columna 7 = monto
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
    
            // Actualizar pie de página (footer)
            $(api.column(7).footer()).html(             // columna 7 = monto
                $.number(pageTotal, 2)
            );
            $(api.column(8).footer()).html(             // columna 8 = ventas
                'de ' + $.number(total, 2)
            );
        }
    })
    
    // Coloca el foco sobre el input buscar
    $('#tablaProductosVer_filter input[type="search"]').focus();
    
    // Clic sobre el boton reload para actualizar la tabla
    $(document).on('click', '#reloadTable', function () {
       dataTable.ajax.reload(null, false);
       $('#tablaProductosVer_filter input[type="search"]').focus();
    })
    
    // clic sobre algunas columnas de la tabla
    $(document).on('click','#tablaProductosVer td',function(e){
        var cell_clicked    = dataTable.cell(this).data();
        var column_clicked  = dataTable.column(this).index();
        var row_clicked     = $(this).closest('tr');
        var row_object      = dataTable.row(row_clicked).data();
        var id              = dataTable.row(row_clicked).data()[1];
        //alert( 'Clic en columna '+column_clicked+' con id '+id+' y valor '+cell_clicked );
        
        if(column_clicked == 5){                        // columna 5 = precio
            $('#modalCostos').modal('show');
            costosProducto(id);
        }
        if(column_clicked == 6){                        // columna 6 = stock
            $('#modalStock').modal('show');
            stockProducto(id);
        }
    });
    
    
    // Agrega producto a la lista de venta
    $(document).on('click','#btnAgregarVenta',function(e){
        var key = $(this).attr('key');
        $.ajax({
            type : "POST",
            url : "api/agregarProductoListaVenta",  // Endpoint PuntoDeVenta
            dataType: 'json',
            data : {key:key},
            success:function(json){
                if(json.status == 'success'){
                    var url = window.location.origin;
                    window.location.href = url+'/admin/puntoDeVenta';
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
                                closeModal: true
                            }
                        }
                    })
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        });
    });
    
    // Obtiene los costos de algun producto
    function costosProducto(id) {
        $.ajax({
            url: "api/costosProducto",  // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id },
            beforeSend: function () {
                $("#modalCostos table#informacionDescuentos").html('<tbody><thead><tr><th class="text-center"><i class="fas fa-spinner fa-spin fa-lg text-muted"></i></th></tr></thead></tbody>');
            },
            success: function (json) {
                $("#modalCostos table#informacionDescuentos").html(json.infoCostos);
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        })
    }
    
    // Obtiene el stock de algun producto
    function stockProducto(id) {
        $.ajax({
            url: "api/stockProducto",   // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id },
            beforeSend: function () {
                
                $("#modalStock table#informacionStock").html('<tbody><thead><tr><th class="text-center"><i class="fas fa-spinner fa-spin fa-lg text-muted"></i></th></tr></thead></tbody>');
                $("#modalStock table#movimientos").html('<tbody><thead><tr><th class="text-center"><i class="fas fa-spinner fa-spin fa-lg text-muted"></i></th></tr></thead></tbody>');
                
            },
            success: function (json) {
                
                $("#modalStock table#informacionStock").html(json.infoStock);
                $("#modalStock table#movimientos").html(json.ultimos_movimientos);
                
                if(json.cant_mov > 0){
                    $("#descargar_mov").removeClass('hidden');
                    $("#descargar_mov").attr('key', json.id_producto);
                }else{
                    $("#descargar_mov").addClass('hidden');
                    $("#descargar_mov").removeAttr('key');
                }
                
                if(json.cant_mov > 5){
                    $("#todos_mov").removeClass('hidden');
                    $("#todos_mov").attr('key', json.id_producto);
                }else{
                    $("#todos_mov").addClass('hidden');
                    $("#todos_mov").removeAttr('key');
                }
                
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        })
    }
    
    // Obtiene los movimientos de un producto
    $(document).on('click','#todos_mov',function(e){
        var key = $(this).attr('key');
        $.ajax({
            type : "POST",
            url : "api/movimientosProducto",    // Endpoint Productos
            dataType: 'json',
            data : {key:key},
            beforeSend: function () {
                $("#modalStock table#movimientos").html('<tbody><thead><tr><th class="text-center"><i class="fas fa-spinner fa-spin fa-lg text-muted"></i></th></tr></thead></tbody>');
            },
            success:function(json){
                if(json.status == 'success'){
                    $("#todos_mov").addClass('hidden');
                    $("#modalStock table#movimientos").html(json.todos_movimientos);
                }
            },
            error : function(xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        });
    });
    
    $(document).on('click','#descargar_mov',function(e){
        var key = $(this).attr('key');
        var url = window.location.origin;
        window.location.href = url+'/admin/productos/movimientos/'+key;
    });
    
    // Poner en liquidacion
    $(document).on('change','#liquidacion',function(e){
        var key = $(this).attr('key');
        var liquidacion = $(this).val();
        ponerLiquidacion(key, liquidacion);
    });
    function ponerLiquidacion(id, liquidacion) {
        $.ajax({
            url: "api/liquidacion", // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id, liquidacion: liquidacion },
            success: function (json) {
                if (json.status == 'error') {
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
                    })
                }else{
                    $('#modalEditar').modal('hide');
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
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function () {
                dataTable.ajax.reload(null, false);
            }
        });
    }
    
    // Marcar producto como novedad
    $(document).on('change','#novedad',function(e){
        var key = $(this).attr('key');
        var novedad = $(this).val();
        novedades(key, novedad);
    });
    function novedades(id, novedad) {
        $.ajax({
            url: "api/novedad", // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id, novedad: novedad },
            success: function (json) {
                if (json.status == 'error') {
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
                    })
                }else{
                    $('#modalEditar').modal('hide');
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
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function () {
                dataTable.ajax.reload(null, false);
            }
        });
    }
    
    // Cambiar estado de anticipo
    $(document).on('change','#anticipo',function(e){
        var key = $(this).attr('key');
        var anticipo = $(this).val();
        cambiarAnticipo(key, anticipo);
    });
    function cambiarAnticipo(id, anticipo) {
        $.ajax({
            url: "api/anticipoProducto",    // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id, anticipo: anticipo },
            success: function (json) {
                if (json.status == 'error') {
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
                    $('#modalEditar').modal('hide');
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
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function () {
                dataTable.ajax.reload(null, false);
            }
        });
    }
    
    // Descontinuar producto
    $(document).on('click','#descontinuar',function(e){
        var key = $(this).attr('key');
        descontinuar(key);
    });
    function descontinuar(id) {
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de descontinuar el producto?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, descontinuar",
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
                    url: "api/descontinuarProducto",    // Endpoint Productos
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id},
                    beforeSend: function () {
                        $("span.estado" + id).html('<i class="fas fa-spinner fa-spin fa-lg text-muted"></i>');
                    },
                    success: function (json) {
                        if(json.status == 'success'){
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
                        } else if (json.status == 'error') {
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
                            })
                        }
                    },
                    error: function (xhr, status) {
                        console.log('Ha ocurrido un problema interno');
                    },
                    complete: function () {
                        $('#modalEditar').modal('hide');
                        dataTable.ajax.reload(null, false);
                    }
                })
            }
        })
    }

    // Descontinuar producto
    $(document).on('click','#activar',function(e){
        var key = $(this).attr('key');
        activar(key);
    });
    function activar(id) {
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de activar nuevamente el producto?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, activar",
                    value: true,
                    visible: true,
                    className: "btn btn-sm btn-flat font-weight-bold text-uppercase bg-aqua",
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
                    url: "api/activarProducto", // Endpoint Productos
                    method: 'POST',
                    dataType: 'json',
                    data: { id: id},
                    success: function (json) {
                        if(json.status == 'success'){
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
                        } else if (json.status == 'error') {
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
                            })
                        }
                    },
                    error: function (xhr, status) {
                        console.log('Ha ocurrido un problema interno');
                    },
                    complete: function () {
                        $('#modalEditar').modal('hide');
                        dataTable.ajax.reload(null, false);
                    }
                })
            }
        })
    }


    
    // MODAL AGREGAR                    ---------------------------------------------------------------- MODAL AGREGAR
    
    // Activa select 2, para categoria, subcategoria y editorial
    $('.seleccionarCategoria, .seleccionarSubcategoria, .seleccionarAutores, .seleccionarEditorial').select2({
        dropdownParent: $('#modalAgregar #agregar_form'),
        width: "resolve"
    })
    
    // Al estar en select de autores o de editorial se ocultan los divs para agregar autor y editorial
    $(".seleccionarAutores, .seleccionarEditorial").on("select2:open", function (e) {
        $(".divAgregarAutor").addClass('hidden');
        $(".divAgregarEditorial").addClass('hidden');
    })
    
    // DETALLES
    
    // Boton quitar detalles, deshabilitado y oculto
    $("#quitar").attr('disabled', 'disabled');                  
    $("#quitar").addClass('hidden');
    
    // Contador en 2 que son los detalles por defecto que estan en el formulario
    var counter = 2;
    
    // Clic en boton agregar detalle
    $(document).on('click', "#agregar", function () { 
        // Si el contador es mayor a 10, error, solo es posible agregar 10 detalles
        if (counter > 10) {                                     
            swal({
                title: '!ERROR!',
                text: 'Solo es posible agregar 10 detalles',
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
            })
            return false;
        }
        // Agregamos un nuevo div con los campos para el detalle 
        var newdetalle = $(document.createElement('div')).attr("id", 'detalle' + counter);
        newdetalle.after().html('<div class="row"><hr><div class="col-xs-12"><div class="form-group"><input type="text" name="dn[]" class="text-uppercase dn form-control" placeholder="Nombre de la característica" autocomplete="off"></div></div><div class="col-xs-12"><div class="form-group"><input type="text" name="dd[]" class="text-uppercase dd form-control" placeholder="Información de la característica" autocomplete="off"></div></div></div>');
        newdetalle.appendTo("#grupoDetalles");
        // El contador aumenta en 1
        counter++;
        // Si el contador es mayor a 2, activa y muestra el boton quitar
        if (counter > 2) {
            $("#quitar").removeAttr('disabled');
            $("#quitar").removeClass('hidden');
        }
    });
    
    // Clic en boton quitar detalle
    $(document).on('click', "#quitar", function () {                            
        // Si el contador es igual a 2, error, no se pueden eliminar los ultimos 2 detalles
        if (counter == 2) {
            swal({
                title: '!Oops!',
                text: 'No hay más detalles a eliminar',
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
            })
            return false;
        }
        // El contador reduce en 1
        counter--;
        // Quitamos el div del detalle correspondiente
        $("#detalle" + counter).remove();
        // Si el contador es igual a 2, desactiva y oculta el boton quitar
        if (counter == 2) {
            $("#quitar").attr('disabled', 'disabled');
            $("#quitar").addClass('hidden');
        }
    });
    
    // CATEGORIA
    
    // Obtener subcategorías según categoría seleccionada
    $(document).on('change', '.seleccionarCategoria', function () {
        categoria = $(this).val();
        $.ajax({
            url: "api/selectSubcategorias", // Endpoint Subcategorias
            method: "POST",
            dataType: "json",
            data: { categoria: categoria },
            beforeSend: function () {
                $(".seleccionarSubcategoria").select2("destroy");
                $(".seleccionarSubcategoria").select2();
                $('.seleccionarSubcategoria').prop("disabled", true);
            },
            success: function (json) {
    
                if (json.status == 'vacio') {
                    $('.seleccionarSubcategoria').html(json.select);
                }
    
                if (json.status == 'lleno') {
                    $('.seleccionarSubcategoria').html(json.select);
                }
    
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function(){
                $('.seleccionarSubcategoria').prop("disabled", false);
            }
        })
    })

    
    
    
    
    // AGREGAR PRODUCTO                    ---------------------------------------------------------------- AGREGAR PRODUCTO            
    /* Agregar producto */
    $('#enviar, #enviar2').click(function (e) {
        e.defaultPrevented;
        agregarProducto();
    });
    function agregarProducto() {
        var formData = new FormData();
        
        // codigo
        formData.append("codigoP", $("#codigoP").val());
        // producto
        formData.append("nombreP", $("#nombreP").val());
        // leyenda
        formData.append("leyenda", $("#leyenda").val());
        // editorial
        formData.append("editorial", $("#editorial").val());
        // autores
        formData.append("autores", $("#autores").val());
        // costo
        formData.append("precioCompra", $("#precioCompra").val());
        // precio
        formData.append("precio", $("#precio").val());
        // stock minimo
        formData.append("stock_minimo", $("#stock_minimo").val());
        // entradas (si se quiere cargar a la lista de entradas)
        formData.append("entradas", $("#entradas").val());
        // categoria
        formData.append("categoria", $("#categoria").val());
        // subcategoria
        formData.append("subcategoria", $("#subcategoria").val());
        // Detalles (caracteristicas)
        var dn = new Array();
        $('input[name^="dn"]').each(function () {
            dn.push($(this).val());
        });
        var dd = new Array();
        $('input[name^="dd"]').each(function () {
            dd.push($(this).val());
        });
        function toObject(names, values) {
            var result = {};
            if (names.length == values.length) {
                for (var i = 0; i < names.length; i++) {
                    if (names[i] != '' && values[i] != '') {
                        result[names[i]] = values[i];
                    }
                }
            }
            return result;
        }
        formData.append("dn", JSON.stringify(dn));
        formData.append("dd", JSON.stringify(dd));
        formData.append("detalles", JSON.stringify(toObject(dn, dd)));
        // Descripcion
        formData.append("desc", $("#desc").val());
        // Palabras clave
        formData.append("pClave", $("#pClave").val());
        // Imagen
        formData.append("imagen", $("#imagen")[0].files[0]);

        $.ajax({
            type: "POST",
            url: "api/agregarProducto", // Endpoint Productos
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $("#enviar, #enviar2").attr("disabled", true);
            },
            success: function (json) {
                // si success
                if (json.status == 'success') {
                    // ocultar modal agregar
                    $('#modalAgregar').modal('hide');
                    // si redireccionar 1 (se agrego cantidad en entradas)
                    if(json.redireccionar == 1){
                        var url = window.location.origin;
                        window.location.href = url+'/admin/registrarCompras';
                    }else{
                        dataTable.ajax.reload();
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
                        setTimeout(function(){ 
                            location.reload();
                        }, 1200);
                    }
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
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function () {
                $("#enviar, #enviar2").removeAttr("disabled");
            }
        })
    }

    // OBTENER PRODUCTO                    ---------------------------------------------------------------- OBTENER PRODUCTO 
    /* Cargar datos en modal editar */
    $(document).on('click', '.obtenerProducto', function () {
        var id = $(this).attr("key");
        $.ajax({
            url: "api/obtenerProducto", // Endpoint Productos
            method: 'POST',
            dataType: 'json',
            data: { id: id},
            beforeSend: function () {
                $("#editar_form").html('<p class="text-center">Cargando datos del producto...</p>');
            },
            success: function (json) {
                if (json.status == 'error') {
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
                    })
                    .then((value) => {
                        if (value) {
                            $('#modalEditar').modal('hide');
                        }
                    });
                } else if (json.status == "success") {
                    
                    // Coloca el formulario con los datos del producto
                    $("#editar_form").html(json.formulario);
                    $("#id_libro_editar").html(json.id_libro_editar);
                    
                    // Al estar en select de autores o de editorial se ocultan los divs para agregar autor y editorial
                    $(".eSeleccionarAutores, .eSeleccionarEditorial").on("select2:open", function (e) {
                        $(".diveAgregarAutor").addClass('hidden');
                        $(".diveAgregarEditorial").addClass('hidden');
                    });
    
                    // Función para mostrar y ocultar div para agregar editorial en el modal editar
                    $('.eagregarEditorial').on('click', function (e) {
                        $(".diveAgregarEditorial").toggleClass('hidden');
                        $(".diveAgregarAutor").addClass('hidden');
                        $("#enuevaEditorial").focus();
                    })
                    
                    // Función para registrar nueva editorial en el modal editar
                    $('#eregistrarEditorial').on('click', function (e) {
                        var editorial = $("#enuevaEditorial").val();
                        $.ajax({
                            type: "POST",
                            url: "api/agregarEditoriales", // Endpoint Editoriales
                            dataType: 'json',
                            data: { nombreE: editorial },
                            beforeSend: function () {
                                $("#eregistrarEditorial").attr("disabled", true);
                            },
                            success: function (json2) {
                                if (json2.status == 'success') {
                                    $(".diveAgregarEditorial").toggleClass('hidden');
                                    obtenerEditoriales(json2.agregado, 'editar');
                                } else {
                                    swal({
                                        title: json2.title,
                                        text: json2.message,
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
                                $("#enuevaEditorial").val("");
                            },
                            error: function (xhr, status) {
                                console.log('Ha ocurrido un problema interno');
                            },
                            complete: function () {
                                $("#eregistrarEditorial").removeAttr("disabled");
                            }
                        })
                    })
    
                    // Función para mostrar y ocultar div para agregar autor en el modal agregar
                    $('.eagregarAutor').on('click', function (e) {
                        $(".diveAgregarAutor").toggleClass('hidden');
                        $(".diveAgregarEditorial").addClass('hidden');
                        $("#enuevoAutor").focus();
                    })
                    // Función para registrar nuevo autor en el modal agregar
                    $('#eregistrarAutor').on('click', function (e) {
                        var autor = $("#enuevoAutor").val();
                        
                        $.ajax({
                            type: "POST",
                            url: "api/agregarAutores",  // Endpoint Autores
                            dataType: 'json',
                            data: { nombreA: autor },
                            beforeSend: function () {
                                $("#eregistrarAutor").attr("disabled", true);
                                $("#editar2").attr("disabled", true);
                            },
                            success: function (json3) {
                                if (json3.status == 'success') {
                                    $(".diveAgregarAutor").toggleClass('hidden');
                                    obtenerAutores(json3.agregado, 'editar');
                                } else {
                                    swal({
                                        title: json3.title,
                                        text: json3.message,
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
                                $("#enuevoAutor").val("");
                                setTimeout(function(){ 
                                    $("#editar2").removeAttr("disabled");
                                }, 800);

                            },
                            error: function (xhr, status) {
                                console.log('Ha ocurrido un problema interno');
                            },
                            complete: function () {
                                $("#eregistrarAutor").removeAttr("disabled");
                            }
                        })
                    })
    
                    // Activar select2 en modal editar
                    $('.eSeleccionarCategoria, .eSeleccionarSubcategoria, .eSeleccionarEditorial, .eSeleccionarAutores').select2({
                        dropdownParent: $('#modalEditar #editar_form'),
                        width: "resolve"
                    });

                    // Activar tagsinput en modal editar
                    $('.tagsinput').tagsinput({
                        maxTags: 10,
                        confirmKeys: [13, 44],
                        cancelConfirmKeysOnEmpty: false,
                        trimValue: true
                    });
    
                    // Activar funciones de detalles en modal editar
                    var eCounter = json.cD + 1;
                    if (eCounter <= 1) {
                        $("#eQuitar").attr('disabled', 'disabled');
                        $("#eQuitar").addClass('hidden');
                    }
                    $(document).on('click', "#eAgregar", function (e) {
                        
                        e.defaultPrevented;
                        e.stopImmediatePropagation();
                        
                        if (eCounter > 10) {
                            swal({
                                title: '!Oops!',
                                text: 'Solo es posible agregar 10 detalles',
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
                            })
                            return false;
                        }
                        var newdetalle = $(document.createElement('div')).attr("id", 'eDetalle' + eCounter);
                        newdetalle.after().html('<div class="row"><hr><div class="col-xs-12"><div class="form-group"><input type="text" name="edn[]" class="text-uppercase dn form-control" placeholder="Nombre de la característica" autocomplete="off"></div></div><div class="col-xs-12"><div class="form-group"><input type="text" name="edd[]" class="text-uppercase dd form-control tagsinput" data-role="tagsinput" placeholder="Información de la característica" autocomplete="off"></div></div></div>');
                        newdetalle.appendTo("#eGrupoDetalles");
                        eCounter++;
                        if (eCounter > 1) {
                            $("#eQuitar").removeAttr('disabled');
                            $("#eQuitar").removeClass('hidden');
                        }
                    });
                    
                    $(document).on('click', "#eQuitar", function () {
                        if (eCounter == 0) {
                            swal({
                                title: '!Oops!',
                                text: 'No hay más detalles a eliminar',
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
                            })
                            return false;
                        }
                        eCounter--;
                        $("#eDetalle" + eCounter).remove();
                        if (eCounter == 1) {
                            $("#eQuitar").attr('disabled', 'disabled');
                            $("#eQuitar").addClass('hidden');
                        }
                    });
                    /* /Activar funciones de detalles en modal editar*/
    
                    /* Activar la función al cambiar categoría en modal editar*/
                    $(document).on('change', '.eSeleccionarCategoria', function () {
                        categoria = $(this).val();
                        $.ajax({
                            url: "api/selectSubcategorias", // Endpoint Subcategorias
                            method: "POST",
                            dataType: "json",
                            data: { categoria: categoria },
                            beforeSend: function () {
                                $(".eSeleccionarSubcategoria").select2("destroy");
                                $(".eSeleccionarSubcategoria").select2();
                                $('.eSeleccionarSubcategoria').prop("disabled", true);  
                            },
                            success: function (json4) {
                                if (json4.status == 'vacio') {
                                    $('.eSeleccionarSubcategoria').html(json4.select);
                                }
                                if (json4.status == 'lleno') {
                                    $('.eSeleccionarSubcategoria').html(json4.select);
                                }
                            },
                            error: function (xhr, status) {
                                console.log('Ha ocurrido un problema interno');
                            },
                            complete: function() {
                                $('.eSeleccionarSubcategoria').prop("disabled", false); 
                            }
                        })
                    })
                    /* /Activar la función al cambiar categoría en modal editar*/
    
                    /* Activar inputmask en modal editar*/
                    $("#ePrecioCompra").inputmask({
                        alias: "decimal",
                        integerDigits: 5,
                        digits: 2,
                        digitsOptional: false,
                        allowPlus: false,
                        allowMinus: false,
                        placeholder: "0",
                        "oncomplete": function () {
                            if ($(this).val() > 0) {
                                $("#ePrecio").removeAttr('disabled');
                            }
                        },
                        "oncleared": function () {
                            $(this).val('');
                            $("#ePrecio").attr('disabled', 'disabled');
                            $("#ePrecio").val('');
                        }
                    });
    
                    $("#ePrecio").inputmask({
                        alias: "decimal",
                        integerDigits: 5,
                        digits: 2,
                        digitsOptional: false,
                        allowPlus: false,
                        allowMinus: false,
                        placeholder: "0",
                        "oncomplete": function () {
                            if ($(this).val() > 0) {
                                $("#eOferta").removeAttr('disabled');
                            }
                        },
                        "oncleared": function () {
                            $(this).val('');
                            $("#eOferta").val(0);
                            $("#eOferta").attr('disabled', 'disabled');
                            $(".eOfertaDetalles").removeClass("show").addClass("hidden");
                        }
                    });
    
                    $("#eStock_minimo").inputmask("9{1,5}", {
                        "placeholder": "0",
                        "rightAlign": false,
                        "oncleared": function () {
                            $(this).val('');
                        }
                    });
                    /* /Activar inputmask en modal editar*/
    
                    /* Activar inputmask para porcentaje y precio de oferta en modal editar*/
                    $("#eoPorcentaje").inputmask({
                        alias: "decimal",
                        integerDigits: 2,
                        digits: 2,
                        digitsOptional: false,
                        allowPlus: false,
                        allowMinus: false,
                        "placeholder": "0",
                        "oncomplete": function () {
                            $("#eoPrecio").attr("disabled", "disabled");
                            $("#eoPrecio").val("");
                        },
                        "oncleared": function () {
                            $("#eoPrecio").removeAttr("disabled");
                        }
                    });
                    $("#eoPrecio").inputmask("decimal", {
                        integerDigits: 5,
                        digits: 2,
                        digitsOptional: false,
                        allowPlus: false,
                        allowMinus: false,
                        placeholder: "0",
                        "oncomplete": function () {
                            $("#eoPorcentaje").attr("disabled", "disabled");
                            $("#eoPorcentaje").val("");
                        },
                        "oncleared": function () {
                            $("#eoPorcentaje").removeAttr("disabled");
                        }
                    });
                    /* /Activar inputmask para porcentaje y precio de oferta en modal editar*/
    
                    /* Activar inputMask y datapicker para decha de oferta en modal editar*/
                    $("#eoFecha").inputmask("9999-99-99", {
                        "placeholder": "yyyy-mm-dd"
                    });
                    $(".datepicker").datepicker({
                        format: 'yyyy-mm-dd',
                        startDate: '0d',
                        language: 'es',
                        clearBtn: true,
                        calendarWeeks: true,
                        autoclose: true
                    });
                    /* /Activar inputMask y datapicker para decha de oferta en modal editar*/
    
                    /* Activar eventos al cambiar de oferta 0 a 1 y viceversa en modal editar*/
                    $(document).on('change', '#eOferta', function () {
                        var oferta = $(this).val();
                        if (oferta == 1) {
                            $(".eOfertaDetalles").removeClass("hidden").addClass("show");
                        } else {
                            $(".eOfertaDetalles").removeClass("show").addClass("hidden");
                        }
                    });
                    /* Activar eventos al cambiar de oferta 0 a 1 y viceversa en modal editar*/
    
                }
            },
            complete: function () {
                $('[data-toggle="tooltip"]').tooltip();
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            }
        })
    });

    // EDITAR PRODUCTO                    ---------------------------------------------------------------- EDITAR PRODUCTO 
    /* Editar producto */
    $('#editar, #editar2').click(function (e) {
        e.defaultPrevented;
        editarProducto();
    });
    function editarProducto() {
    
        var formData = new FormData();

        formData.append("idP", $("#idP").val());
        formData.append("idCb", $("#idCb").val());
        formData.append("codigoP", $("#eCodigoP").val());
        formData.append("nombreP", $("#eNombreP").val());
        formData.append("leyenda", $("#eLeyenda").val());
        formData.append("editorial", $("#eEditorial").val());
        formData.append("autores", $("#eAutores").val());
        formData.append("precioCompra", $("#ePrecioCompra").val());
        formData.append("precio", $("#ePrecio").val());
        formData.append("stock_minimo", $("#eStock_minimo").val());
        formData.append("categoria", $("#eCategoria").val());
        formData.append("subcategoria", $("#eSubcategoria").val());
        // DETALLES
        var dn = new Array();
        $('input[name^="edn"]').each(function () {
            dn.push($(this).val());
        });
        var dd = new Array();
        $('input[name^="edd"]').each(function () {
            dd.push($(this).val());
        });
        function toObject(names, values) {
            var result = {};
            if (names.length == values.length) {
                for (var i = 0; i < names.length; i++) {
                    if (names[i] != '' && values[i] != '') {
                        result[names[i]] = values[i];
                    }
                }
            }
            return result;
        }
        formData.append("dn", JSON.stringify(dn));
        formData.append("dd", JSON.stringify(dd));
        formData.append("detalles", JSON.stringify(toObject(dn, dd)));
    
        formData.append("desc", $("#eDesc").val());
        formData.append("pClave", $("#epClave").val());
        formData.append("imagen", $("#eImagen")[0].files[0]);
        
        formData.append("ofertaBD", $("#ofertaBD").val());
        formData.append("oferta", $("#eOferta").val());
        formData.append("oPrecio", $("#eoPrecio").val());
        formData.append("oPorcentaje", $("#eoPorcentaje").val());
        formData.append("oFecha", $("#eoFecha").val());
    
        $.ajax({
            type: "POST",
            url: "api/editarProducto", // Endpoint Productos
            dataType: 'json',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $("#editar, #editar2").attr('disabled', 'disabled');
            },
            success: function (json) {
                if (json.status == 'success') {
                    $('#modalEditar').modal('hide');
                    dataTable.ajax.reload(null, false);
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
                    })
                    .then((value) => {
                        if (value) {
                            $("#editar_form")[0].reset();
                            $(".eOfertaDetalles").removeClass("show").addClass("hidden");
                        }
                    });
                }
            },
            error: function (xhr, status) {
                console.log('Ha ocurrido un problema interno');
            },
            complete: function () {
                $("#editar, #editar2").removeAttr("disabled");
            }
        });
    }
    
    // ELIMINAR PRODUCTO                    ---------------------------------------------------------------- ELIMINAR PRODUCTO  
    $(document).on('click', '.eliminar', function (e) {
        e.defaultPrevented;
        var id = $(this).attr("key");
        swal({
            title: "!Atención¡",
            text: "¿Estás seguro de eliminar el producto?",
            icon: 'warning',
            closeOnClickOutside: false,
            closeOnEsc: false,
            buttons: {
                confirm: {
                    text: "Sí, eliminar",
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
                    type: "POST",
                    url: "api/eliminarProducto", // Endpoint Productos
                    dataType: 'json',
                    data: { id: id },
                    beforeSend: function () {

                    },
                    success: function (json) {
                        if (json.status == 'success') {
                            dataTable.ajax.reload(null, false);
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
                        if (json.status == 'info') {
                            swal({
                                title: json.title,
                                text: json.message,
                                icon: "info",
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
                    error: function (xhr, status) {
                        console.log('Ha ocurrido un problema interno');
                    }
                });
            }
        });
    });
    
});
/* __________________________________________________________________________________________________________ */
    
    


    
/* CAMBIO DE INPUT FILE IMAGEN modal agregar */
$("#imagen").change(function () {
    imagen = this.files[0];
    if (imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#imagen").val(null);
        swal({
            title: "ERROR!",
            text: "El formato del archivo no corresponde a una imagen.",
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
    } else if (imagen["size"] > 5000000) {
        $("#imagen").val(null);
        swal({
            title: "ERROR!",
            text: "El tamaño de la imagen no debe superar los 5 MB.",
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
    } else {
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);
        $(datosImagen).on("load", function (event) {
            var rutaImagen = event.target.result;
            var image = new Image();
            image.src = rutaImagen;
            image.onload = function () {
                $(".previsualizarImagen").attr("src", rutaImagen);
            }
        })
    }
});
/* /.CAMBIO DE INPUT FILE IMAGEN modal agregar */
    
/* CAMBIO DE INPUT FILE IMAGEN modal editar */
$(document).on('change', '#eImagen', function () {
    imagen = this.files[0];
    if (imagen["type"] != 'image/jpeg' && imagen["type"] != 'image/png') {
        $("#eImagen").val(null);
        swal({
            title: "ERROR!",
            text: "El formato del archivo no corresponde a una imagen.",
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
    } else if (imagen["size"] > 5000000) {
        $("#eImagen").val(null);
        swal({
            title: "ERROR!",
            text: "El tamaño de la imagen no debe superar los 5 MB.",
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
    } else {
        var datosImagen = new FileReader;
        datosImagen.readAsDataURL(imagen);
        $(datosImagen).on("load", function (event) {
            var rutaImagen = event.target.result;
            var image = new Image();
            image.src = rutaImagen;
            image.onload = function () {
                $(".ePrevisualizarImagen").attr("src", rutaImagen);
            }
        })
    }
});
/* /.CAMBIO DE INPUT FILE IMAGEN modal editar */
    
$("#entradas").inputmask("9{1,3}",{ 
    "placeholder": "0",
    "rightAlign": false,
    "oncleared": function(){ 
        $(this).val('');  
    }
});
    
$("#precioCompra").inputmask({
    alias: "decimal",
    integerDigits: 5,
    digits: 2,
    digitsOptional: false,
    allowPlus: false,
    allowMinus: false,
    placeholder: "0",
    "oncomplete": function () {
        if ($(this).val() > 0) {
            $("#precio").removeAttr('disabled');
            $("#precio").val($(this).val());
        }
    },
    "oncleared": function () {
        $(this).val('');
        $("#precio").attr('disabled', 'disabled');
        $("#precio").val('');
    }
});
    
$("#precio").inputmask({
    alias: "decimal",
    integerDigits: 5,
    digits: 2,
    digitsOptional: false,
    allowPlus: false,
    allowMinus: false,
    placeholder: "0",
    "oncomplete": function () {
        if ($(this).val() > 0) {
            $("#oferta").removeAttr('disabled');
        }
    },
    "oncleared": function () {
        $(this).val('');
        $("#oferta").val(0);
        $("#oferta").attr('disabled', 'disabled');
        $(".ofertaDetalles").removeClass("show").addClass("hidden");
        $("#oPrecio").removeAttr("disabled");
        $("#oPrecio").val(0);
        $("#oPorcentaje").removeAttr("disabled");
        $("#oPorcentaje").val(0);
        $("#oFecha").val('');
    }
});
    
$("#stock_minimo").inputmask("9{1,5}", {
    "placeholder": "0",
    "rightAlign": true,
    "oncleared": function () {
        $(this).val('');
    }
});
    
/* Mostrar u ocultar div con campos para la oferta modal agregar */
$("#oferta").change(function () {
    var oferta = $(this).val();
    if (oferta == 1) {
        $(".ofertaDetalles").removeClass("hidden").addClass("show");
        $("#oPrecio").removeAttr("disabled");
        $("#oPorcentaje").removeAttr("disabled");
    } else {
        $(".ofertaDetalles").removeClass("show").addClass("hidden");
        $("#oPrecio").val("");
        $("#oPrecio").removeAttr("disabled");
        $("#oPorcentaje").val("");
        $("#oPorcentaje").removeAttr("disabled");
        $("#oFecha").val("");
    }
});
/* /.Mostrar u ocultar div con campos para la oferta */
    
/* Acciones para los campos precio y procentaje de oferta modal agregar */
$("#oPorcentaje").inputmask("decimal", {
    "placeholder": "0",
    "oncomplete": function () {
        $("#oPrecio").attr("disabled", "disabled");
        $("#oPrecio").val("");
    },
    "oncleared": function () {
        $("#oPrecio").removeAttr("disabled");
        $("#oPrecio").val("");
    }
    });
    $("#oPrecio").inputmask("decimal", {
    integerDigits: 5,
    digits: 2,
    digitsOptional: false,
    allowMinus: false,
    placeholder: "0",
    "oncomplete": function () {
        $("#oPorcentaje").attr("disabled", "disabled");
        $("#oPorcentaje").val("");
    },
    "oncleared": function () {
        $("#oPorcentaje").removeAttr("disabled");
        $("#oPorcentaje").val("");
    }
});
/* /.Acciones para los campos precio y procentaje de oferta modal agregar */
    
/* Configuración del campo fecha fin oferta modal agregar */
$("#oFecha").inputmask("9999-99-99", {
    "placeholder": "yyyy-mm-dd"
    });
    $(".datepicker").datepicker({
    format: 'yyyy-mm-dd',
    startDate: '0d',
    language: 'es',
    clearBtn: true,
    calendarWeeks: true,
    autoclose: true
});
/* /.Configuración del campo fecha fin oferta modal agregar */
    
// Función para obtener las editoriales
function obtenerEditoriales(seleccionado = 0, modal = 'agregar') {
    var metodo = 'cargar';
    $.ajax({
        type: "POST",
        url: "api/obtenerEditoriales", // Endpoint Productos (Cambiar a Editoriales)
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
        url: "api/agregarEditoriales", // Endpoint Editoriales
        dataType: 'json',
        data: { nombreE: editorial },
        beforeSend: function () {
            $("#registrarEditorial").attr("disabled", true);
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
    if(modal == 'agregar'){
        var autoresSeleccionados = $("#autores").val();
    }else if(modal == 'editar'){
        var autoresSeleccionados = $("#eAutores").val();
    }

    $.ajax({
        type: "POST",
        url: "api/obtenerAutores", // Endpoint Productos (Cambiar a Autores)
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
        url: "api/agregarAutores", // Endpoint Autores
        dataType: 'json',
        data: { nombreA: autor },
        beforeSend: function () {
            $("#registrarAutor").attr("disabled", true);
            $("#enviar2").attr("disabled", true);
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
                $("#enviar2").removeAttr("disabled");
            }, 800);
        },
        error: function (xhr, status) {
            console.log('Ha ocurrido un problema interno');
        },
        complete: function () {
            $("#registrarAutor").removeAttr("disabled");
        }
    })
})
    
/* Limpiar el formulario del modal agregar */
$('.agregarProducto').on('click', function (e) {
    $("#oferta").val(0);
    $("#oferta").attr("disabled", "disabled");
    $("#agregar_form")[0].reset();
    
    $('.seleccionarAutores').val(null).trigger('change');
    $("#nuevoAutor").val("");
    $('.seleccionarEditorial').val(null).trigger('change');
    $("#nuevaEditorial").val("");
    $('.seleccionarCategoria').val(null).trigger('change');
    $('.seleccionarSubcategoria').val(null).trigger('change');
    
    $("#oPrecio").removeAttr("disabled");
    $("#oPorcentaje").removeAttr("disabled");
    $(".ofertaDetalles").removeClass("show").addClass("hidden");
    $('.tagsinput').tagsinput('removeAll');
    
    $("#precio").attr('disabled', 'disabled');
    $("#precioCompra").attr('disabled', 'disabled');
    
    $("#stock_minimo").removeAttr('disabled');
    
    $("#precioCompra").removeAttr('disabled');
    $("#precio").attr('disabled', 'disabled');
    
    obtenerEditoriales();
    obtenerAutores();
    
    $(".select2-search__field").css("width", "100%");

});
    
$('.tagsinput').tagsinput({
    maxTags: 10,
    confirmKeys: [13, 44],
    cancelConfirmKeysOnEmpty: false,
    trimValue: true
});