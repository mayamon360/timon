// Create a Stripe client.
var stripe = Stripe('pk_live_51I0rtnJzLrrQorlSjs3SZ0qQlKJtbaOqSGraa6WEktPZbai3iDEflVn6NWZqI2aKnx8faSl4uPBDY5XGH9azNRWP00GR8P9pU4');

// Create an instance of Elements.
var elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
// (Note that this demo uses a wider set of styles than the guide below.)
var style = {
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    fontSize: '13px',
    '::placeholder': {
      color: '#aab7c4'
    }
  },
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');
// Handle real-time validation errors from the card Element.
card.on('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
    event.preventDefault();
    
    $("#card-element").addClass('d-none');
    $(".div_solicitar_vale").addClass('d-none');
    $(".div_pagar_tarjeta").addClass('d-none');
    $(".cargando_").removeClass('d-none');
    
    stripe.createToken(card).then(function(result) {
        if (result.error) {
            // Inform the user if there was an error.
            var errorElement = document.getElementById('card-errors');
            errorElement.textContent = result.error.message;
            
            $("#card-element").removeClass('d-none');
            $(".div_solicitar_vale").removeClass('d-none');
            $(".div_pagar_tarjeta").removeClass('d-none');
            $(".cargando_").addClass('d-none');
            
        } else {
            // Send the token to your server.
            stripeTokenHandler(result.token);
        }
    });
});

// Submit the form with the token ID.
function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    // Submit the form
    form.submit();
}


$(".div_solicitar_vale button").on('click', function(){
     $.ajax({
        url:"api/solicitarVale",
        method:'GET',
        dataType: 'json',
        beforeSend: function(){
            $(".div_solicitar_vale").addClass('d-none');
            $(".cargando").removeClass('d-none');
        },
        success:function(json){
            if(json.status == 'error'){
                location.href ="./compra?error=compra_vacia";
            }else{
                $("#name").val(json.name);
                $("#email").val(json.email);
                $("#intent").val(json.intent);
                $("#id_stripe").val(json.id_stripe);   
            }
        },
        error : function(xhr, status) {
            alert('Ha ocurrido un problema interno');
        },
        complete: function(){ 
            $("#payment-form").addClass('d-none');
            $(".cargando").addClass('d-none');
            $("#payment-form-oxxo").removeClass('d-none');
        }
    })   
})

var form_oxxo = document.getElementById('payment-form-oxxo');

form_oxxo.addEventListener('submit', function(event) {
    event.preventDefault();
    
    $(".div_imprimir_vale").addClass('d-none');
    $(".cargando").removeClass('d-none');
    
    stripe.confirmOxxoPayment(document.getElementById('intent').value,{
        payment_method: {
            billing_details: {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
            },
        },
    }) // Stripe.js will open a modal to display the OXXO voucher to your customer
    .then(function(result) {
        // This promise resolves when the customer closes the modal
        if (result.error) {
            // Display error to your customer
            var errorMsg = document.getElementById('error-message');
            errorMsg.innerText = result.error.message;
            $(".div_imprimir_vale").removeClass('d-none');
            $(".cargando").addClass('d-none');
        } else{
            limpiarCarrito();
        }
    });
});

function limpiarCarrito() {
    $.ajax({
        type : 'GET',
        url : 'api/limpiarCarrito',
        dataType: 'json',
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
        complete : function() {
            location.href ="./cuenta/mis-compras";
        }
    })
}