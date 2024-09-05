 $(document).ready(function(){

 		$("#quitar").attr('disabled', 'disabled');
 		$("#quitar").addClass('hidden');

	    var counter = 4;
		
	    $(document).on('click', "#agregar", function(){
				
			if(counter>10){
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
                            className: "btn btn-sm btn-primary",
                            closeModal: true,
                         }
                    }
                })
		        return false;
		    }   
			
			var newdetalle = $(document.createElement('div')).attr("id", 'detalle' + counter);
                newdetalle.after().html('<div class="row"><hr><div class="col-xs-12"><div class="form-group"><input type="text" name="dn[]" class="dn form-control" placeholder="Nombre de la característica"></div></div><div class="col-xs-12"><div class="form-group"><input type="text" name="dd[]" class="dd form-control" placeholder="Información de la característica"></div></div></div>');
            
			newdetalle.appendTo("#grupoDetalles");
				
		    counter++;

		    if(counter > 4){
		    	$("#quitar").removeAttr('disabled');
		    	$("#quitar").removeClass('hidden');
		    }

			/* HABILITAR PARA USAR TAGSINPUT EN LOS CAMPO DETALLES
		    $('.tagsinput').tagsinput({
			    maxTags: 10,
			    confirmKeys: [13,44],
			    cancelConfirmKeysOnEmpty: false,
			    trimValue: true
			});*/

	    });

	    $(document).on('click', "#quitar", function(){
		    if(counter==4){
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
                            className: "btn btn-sm btn-primary",
                            closeModal: true,
                         }
                    }
                })
		        return false;
		    }   
	        counter--;
			
	        $("#detalle" + counter).remove();

	        if(counter==4){
		        $("#quitar").attr('disabled', 'disabled');
		        $("#quitar").addClass('hidden');
		    }
		});
		
  });