$(document).ready(function(){

    /* Cargar los datos en la tabla */
    var dataTable = $('#tablaUsuarios').DataTable({
        /*buttons: [
            {
                extend: 'excelHtml5',
                title: 'Ventas',
                text: 'Excel',
                className: 'btn btn-default',
                exportOptions: {
                    columns: [0, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13],

                }
            }
        ],*/
        "responsive" : true,
        "order": [],
        "ajax" : {
            url:"api/mostrarUsuarios",
            type:"POST"
        },
        "deferRender": true, 
        "retrieve" : true,
        "processing" : true,
        "columnDefs" : [
            {
                "targets" : [1,6],
                "orderable" : false
            }
        ],
        "language": {
            "decimal":        "",
            "emptyTable":     "No hay datos disponibles en la tabla",
            "info":           "Mostrando _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
            "infoFiltered":   "(filtrado de _MAX_ entradas totales)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "zeroRecords":    "No se encontraron registros coincidentes",
            "paginate": {
                "first":      "Primero",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activar para ordenar la columna ascendente",
                "sortDescending": ": activar para ordenar la columna descendente"
            }
        }
    });
    $("#ExportReporttoExcel").on("click", function(e) {
        e.defaultPrevented;
        //dataTable.button('.buttons-excel').trigger(); Está linea la podemos activar si deseamos que se haga con la acción del botón datatables
    });
    /* /.Cargar los datos en la tabla */

    /* Cambiar el estado de alguna subcategoría */
    $(document).on('click', '.estado', function(){
        var id = $(this).attr("key");
        var estado = $(this).attr("value");
        $.ajax({
            url:"api/estadoUsuario",
            method:'POST',
            dataType: 'json',
            data : {id:id,estado:estado},
            beforeSend: function(){
                $("span.estado"+id).html('<i class="fas fa-spinner fa-spin fa-lg text-muted"></i>');
            },
            success:function(json){
                
                if(json.status=='error'){
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
                    })
                }
                
            },
            error : function(xhr, status) {
                alert('Ha ocurrido un problema interno');
            },
            complete: function(){ 
                dataTable.ajax.reload( null, false );
            }
        })
    });
    /* /.Cambiar el estado de alguna subcategoría */
})
