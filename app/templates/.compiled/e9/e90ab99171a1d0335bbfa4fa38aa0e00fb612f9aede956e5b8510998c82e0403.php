<?php

/* cuenta/mis-compras.twig */
class __TwigTemplate_479481a5dd4b53191ac539be04601a3963e7a29554149a9ca476d226c2eb85b3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "cuenta/mis-compras.twig", 1);
        $this->blocks = array(
            'appHeader' => array($this, 'block_appHeader'),
            'appTitle' => array($this, 'block_appTitle'),
            'appBody' => array($this, 'block_appBody'),
            'appFooter' => array($this, 'block_appFooter'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "overall/layout";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_appHeader($context, array $blocks = array())
    {
        // line 3
        echo "    <link rel=\"stylesheet\" href=\"https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css\">
";
    }

    // line 7
    public function block_appTitle($context, array $blocks = array())
    {
        // line 8
        echo "    <title>Mis compras</title>
";
    }

    // line 11
    public function block_appBody($context, array $blocks = array())
    {
        // line 12
        echo "
    ";
        // line 13
        $this->loadTemplate("cuenta/menu", "cuenta/mis-compras.twig", 13)->display($context);
        // line 14
        echo "
    <main class=\"container mt-3\">
        <p class=\"alert alert-danger mt-2 animated flash\">Tome en cuenta que los pagos en OXXO tardan un máximo de 72 horas en verse reflejados en nuestro sistema. Se recomienda compartir el comprobante de pago y el folio asociado al número de whatsapp 7222549526.</p>
        <table id=\"lista_compras\" class=\"table table-striped table-bordered dt-responsive nowrap\" style=\"width:100%\">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>ID stripe</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Total</th>
                    <th>MP</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        
        <div class=\"modal modal-fluid fade\" id=\"info_pago\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"info_pago\" aria-hidden=\"true\">
            <div class=\"modal-dialog\" role=\"document\">
                <div class=\"modal-content\">
                    
                    <div class=\"modal-header border-bottom\">
                        
                        <h4 class=\"modal-title\">
                            <span class=\"folio text-dark\">...</span>
                        </h4>
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                            <span aria-hidden=\"true\">&times;</span>
                        </button>
                        
                    </div>
                    
                    <div class=\"modal-body p-2\">
                       
                            
                        <div class=\"div_loading_data py-5 text-center\">
                            <span class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <div class=\"div_data_info\">
                            
                        </div>
                            
                        
                    </div>
                </div>
            </div>
        </div>

    </main>
";
    }

    // line 67
    public function block_appFooter($context, array $blocks = array())
    {
        // line 68
        echo "    ";
        // line 69
        echo "    <script src=\"https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js\"></script>
    <script src=\"https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js\"></script>
    <script src=\"https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js\"></script>
    <script src=\"https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js\"></script>
    <script>
        \$(document).ready(function() {
            var dataTable = \$('#lista_compras').DataTable({
                \"responsive\" : true,
                \"order\": [],
                \"ajax\" : {
                    url:\"api/mostrarComprasStripe\",
                    type:\"GET\",
                    \"complete\": function () {
                        \$('[data-toggle=\"tooltip\"]').tooltip();
                    }
                },
                \"deferRender\": true, 
                \"retrieve\" : true,
                \"processing\" : true,
                \"lengthMenu\": [[15, 30], [15, 30]],
                \"iDisplayLength\" : 15, 
                \"columnDefs\" : [
                    {
                        /*\"targets\" : 4,
                        \"className\": \"text-right\"*/
                    }
                ],
                \"language\": {
                    \"decimal\":        \"\",
                    \"emptyTable\":     \"No has realizado compras\",
                    \"info\":           \"Mostrando _START_ al _END_ de _TOTAL_ registros\",
                    \"infoEmpty\":      \"Mostrando 0 a 0 de 0 registros\",
                    \"infoFiltered\":   \"(filtrado de _MAX_ entradas totales)\",
                    \"infoPostFix\":    \"\",
                    \"thousands\":      \",\",
                    \"lengthMenu\":     \"Mostrar _MENU_ registros\",
                    \"loadingRecords\": \"Cargando lista...\",
                    \"processing\":     \"Procesando...\",
                    \"search\":         \"BUSCAR:\",
                    \"zeroRecords\":    \"No se encontraron resultados\",
                    \"paginate\": {
                        \"first\":      \"<<\",
                        \"last\":       \">>\",
                        \"next\":       \">\",
                        \"previous\":   \"<\"
                    },
                    \"aria\": {
                        \"sortAscending\":  \": activar para ordenar la columna ascendente\",
                        \"sortDescending\": \": activar para ordenar la columna descendente\"
                    }
                }
            });
            
            \$('#lista_compras_filter input[type=\"search\"]').focus();
            
            \$(document).on('click', '.info_pago', function(){
                var folio = \$(this).attr('folio');
                \$(\".folio\").html(folio);
                \$.ajax({
                    type : 'GET',
                    url : 'api/mostrarCompra',
                    dataType: 'json',
                    data : {folio:folio},
                    beforeSend: function(){ 
                        \$('.div_loading_data').addClass('d-flex justify-content-center').removeClass('d-none');
                        \$('.div_data_info').addClass('d-none');
                    },
                    success : function(json) {
                        if(json.status == 'success') {
                            
                            \$(\".div_data_info\").html(json.html);
                            
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
                                    \$('#info_pago').modal('hide');
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
                        \$('.div_loading_data').removeClass('d-flex justify-content-center').addClass('d-none');
                        \$('.div_data_info').removeClass('d-none');
                    } 
                })
            })
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "cuenta/mis-compras.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  116 => 69,  114 => 68,  111 => 67,  56 => 14,  54 => 13,  51 => 12,  48 => 11,  43 => 8,  40 => 7,  34 => 3,  31 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}
{% block appHeader %}
    <link rel=\"stylesheet\" href=\"https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.datatables.net/responsive/2.2.5/css/responsive.bootstrap4.min.css\">
{% endblock %}

{% block appTitle %}
    <title>Mis compras</title>
{% endblock %}

{% block appBody %}

    {% include 'cuenta/menu' %}

    <main class=\"container mt-3\">
        <p class=\"alert alert-danger mt-2 animated flash\">Tome en cuenta que los pagos en OXXO tardan un máximo de 72 horas en verse reflejados en nuestro sistema. Se recomienda compartir el comprobante de pago y el folio asociado al número de whatsapp 7222549526.</p>
        <table id=\"lista_compras\" class=\"table table-striped table-bordered dt-responsive nowrap\" style=\"width:100%\">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>ID stripe</th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>Total</th>
                    <th>MP</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        
        <div class=\"modal modal-fluid fade\" id=\"info_pago\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"info_pago\" aria-hidden=\"true\">
            <div class=\"modal-dialog\" role=\"document\">
                <div class=\"modal-content\">
                    
                    <div class=\"modal-header border-bottom\">
                        
                        <h4 class=\"modal-title\">
                            <span class=\"folio text-dark\">...</span>
                        </h4>
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                            <span aria-hidden=\"true\">&times;</span>
                        </button>
                        
                    </div>
                    
                    <div class=\"modal-body p-2\">
                       
                            
                        <div class=\"div_loading_data py-5 text-center\">
                            <span class=\"spinner-grow bg-app mx-auto\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <div class=\"div_data_info\">
                            
                        </div>
                            
                        
                    </div>
                </div>
            </div>
        </div>

    </main>
{% endblock %}

{% block appFooter %}
    {#<script src=\"assets/jscontrollers/cuenta/mis-compras.js\"></script>#}
    <script src=\"https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js\"></script>
    <script src=\"https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js\"></script>
    <script src=\"https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js\"></script>
    <script src=\"https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js\"></script>
    <script>
        \$(document).ready(function() {
            var dataTable = \$('#lista_compras').DataTable({
                \"responsive\" : true,
                \"order\": [],
                \"ajax\" : {
                    url:\"api/mostrarComprasStripe\",
                    type:\"GET\",
                    \"complete\": function () {
                        \$('[data-toggle=\"tooltip\"]').tooltip();
                    }
                },
                \"deferRender\": true, 
                \"retrieve\" : true,
                \"processing\" : true,
                \"lengthMenu\": [[15, 30], [15, 30]],
                \"iDisplayLength\" : 15, 
                \"columnDefs\" : [
                    {
                        /*\"targets\" : 4,
                        \"className\": \"text-right\"*/
                    }
                ],
                \"language\": {
                    \"decimal\":        \"\",
                    \"emptyTable\":     \"No has realizado compras\",
                    \"info\":           \"Mostrando _START_ al _END_ de _TOTAL_ registros\",
                    \"infoEmpty\":      \"Mostrando 0 a 0 de 0 registros\",
                    \"infoFiltered\":   \"(filtrado de _MAX_ entradas totales)\",
                    \"infoPostFix\":    \"\",
                    \"thousands\":      \",\",
                    \"lengthMenu\":     \"Mostrar _MENU_ registros\",
                    \"loadingRecords\": \"Cargando lista...\",
                    \"processing\":     \"Procesando...\",
                    \"search\":         \"BUSCAR:\",
                    \"zeroRecords\":    \"No se encontraron resultados\",
                    \"paginate\": {
                        \"first\":      \"<<\",
                        \"last\":       \">>\",
                        \"next\":       \">\",
                        \"previous\":   \"<\"
                    },
                    \"aria\": {
                        \"sortAscending\":  \": activar para ordenar la columna ascendente\",
                        \"sortDescending\": \": activar para ordenar la columna descendente\"
                    }
                }
            });
            
            \$('#lista_compras_filter input[type=\"search\"]').focus();
            
            \$(document).on('click', '.info_pago', function(){
                var folio = \$(this).attr('folio');
                \$(\".folio\").html(folio);
                \$.ajax({
                    type : 'GET',
                    url : 'api/mostrarCompra',
                    dataType: 'json',
                    data : {folio:folio},
                    beforeSend: function(){ 
                        \$('.div_loading_data').addClass('d-flex justify-content-center').removeClass('d-none');
                        \$('.div_data_info').addClass('d-none');
                    },
                    success : function(json) {
                        if(json.status == 'success') {
                            
                            \$(\".div_data_info\").html(json.html);
                            
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
                                    \$('#info_pago').modal('hide');
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
                        \$('.div_loading_data').removeClass('d-flex justify-content-center').addClass('d-none');
                        \$('.div_data_info').removeClass('d-none');
                    } 
                })
            })
        });
    </script>
{% endblock %}", "cuenta/mis-compras.twig", "/home4/eltimonl/public_html/app/templates/cuenta/mis-compras.twig");
    }
}
