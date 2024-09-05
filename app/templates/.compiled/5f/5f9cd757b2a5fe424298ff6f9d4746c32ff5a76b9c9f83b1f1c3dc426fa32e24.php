<?php

/* compra/procesar.twig */
class __TwigTemplate_fc43141f5c840cd9e3cc89a2c263950de005579c84bf9609f747fdf0176c14d6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "compra/procesar.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'appHeader' => array($this, 'block_appHeader'),
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

    // line 3
    public function block_appTitle($context, array $blocks = array())
    {
        // line 4
        echo "    <title>Mi compra</title>
";
    }

    // line 7
    public function block_appHeader($context, array $blocks = array())
    {
        // line 8
        echo "<style>
.table-cards tr.bg-white>th:after,
.table-cards tr.bg-white>td:after {
    border-top: 1px solid var(--borderColor);
    border-bottom: 1px solid var(--borderColor);
}
.table-hover tr.bg-white:hover {
    background-color: #fff!important;
}
.table-hover tr.bg-white:hover>th,
.table-hover tr.bg-white:hover>td{
    background-color: #f8f9fa!important;
}
.table-cards tr.bg-white.empty>th:after{
    border:none!important;
}
</style>
<link rel=\"stylesheet\" href=\"./assets/plantilla/stripe.css\">
";
    }

    // line 27
    public function block_appBody($context, array $blocks = array())
    {
        // line 28
        echo "
    <main class=\"container px-3 px-md-0 mt-2\">

        <div class=\"row px-0 align-items-center destacados\">
            <div class=\"col-5 col-md-7 col-lg-9\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><small class=\"font-weight-bold\"><span class=\"resultados\"></span></small></p>
                    <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-shopping-bag\"></i> Compra</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-7 col-md-5 col-lg-3 text-right\">
                <a href=\"compra\" class=\"btn btn-sm btn-icon boton_negro\">
                    <i class=\"fas fa-chevron-left\"></i>
                    <span class=\"btn-inner--text\">Modificar compra</span>
                </a>
            </div>
        </div>
        
        <div class=\"row px-0 mt-3\">
            <div class=\"col-12 col-lg-8 order-12 order-lg-1\">
                
                <div class=\"tabla_carrito d-block\">
                    
                </div>
                
            </div>
            <div class=\"col-12 col-lg-4 d-none order-1 order-lg-12 mb-4 mb-lg-0 div_procesar_pago\">
                
                ";
        // line 55
        if (((($context["telefono"] ?? null) == "") || (($context["cp"] ?? null) == ""))) {
            // line 56
            echo "                    <div class=\"alert bg-light\" role=\"alert\">
                        <h4 class=\"alert-heading\">¡Acción necesaria!</h4>
                        <p>Con la finalidad de agilizar el proceso de tu compra, completa los siguientes datos:</p>
                        <ul>
                            <li>Número de teléfono</li>
                            <li>Dirección de envió</li>
                        </ul>
                        <p>Es necesario incluso si la entrega es en sucursal.</p>
                        <hr>
                        <p class=\"mb-0\">Entra a tu perfil y después haz clic en la pestaña <a class=\"btn-link font-weight-bold\" href=\"cuenta/mis-datos\">Mis datos</a>.</p>
                    </div>
                ";
        } else {
            // line 68
            echo "                    ";
            // line 73
            echo "                    
                    <div class=\"p-4 border rounded text-center z-depth-1\">
                        <table class=\"w-100 border-bottom\">
                            <tr>
                                <td class=\"w-50\">Subtotal <span class=\"subtotal\" style=\"font-weight:normal;\">\$ 0.00</span></td>
                                <td><span class=\"envio\" style=\"font-weight:normal;\">--</span></td>
                            </tr>
                        </table>

                        <h1 class=\"mb-4 p-0 text-app\"><span class=\"total\"></span></h1>
                        
                        <form action=\"";
            // line 84
            echo twig_escape_filter($this->env, ($context["url_post"] ?? null), "html", null, true);
            echo "\" method=\"post\" id=\"payment-form\">
                            <div class=\"form-group\">
                                <label for=\"card-element\">
                                    Paga con tarjeta de crédito o débito
                                </label>
                                <div id=\"card-element\">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                ";
            // line 93
            if ((($context["message"] ?? null) != "")) {
                // line 94
                echo "                                    <div id=\"card-errors\" role=\"alert\">";
                echo twig_escape_filter($this->env, ($context["message"] ?? null), "html", null, true);
                echo "</div>
                                ";
            } else {
                // line 96
                echo "                                    <div id=\"card-errors\" role=\"alert\"></div>
                                ";
            }
            // line 98
            echo "                            </div>
                            <div class=\"text-center div_pagar_tarjeta\">
                                <button class=\"btn btn-sm btn-icon boton_color\">
                                    <span class=\"btn-inner--text\">Procesar pago</span>
                                    <span class=\"btn-inner--icon\"><i class=\"far fa-credit-card\"></i></span>
                                </button>
                            </div>
                        </form>
                        <div class=\"cargando_ d-none\">
                            <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <hr class=\"mt-4\">
                        
                        <label>
                            Paga en efectivo con <img src=\"./assets/plantilla/img/general/oxxo.svg\" width=\"35px\">
                        </label>
                        <div class=\"text-center div_solicitar_vale\">
                            <button class=\"btn btn-sm btn-icon boton_color\">
                                <span class=\"btn-inner--text\">Solicitar vale</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-receipt\"></i></span>
                            </button>
                        </div>
                        
                        <div class=\"cargando d-none\">
                            <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <form id=\"payment-form-oxxo\" class=\"d-none\">
                                
                            <input type=\"hidden\" id=\"name\" name=\"name\" value=\"\">
                            <input type=\"hidden\" id=\"email\" name=\"email\" value=\"\">
                            <input type=\"hidden\" id=\"intent\" name=\"intent\" value=\"\">
                            <input type=\"hidden\" id=\"id_stripe\" name=\"id_stripe\" value=\"\">

                            <div class=\"text-center div_imprimir_vale\">
                                <button id=\"submit-button\" class=\"btn btn-sm btn-icon boton_color\">
                                    <span class=\"btn-inner--text\">Imprimir vale</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-receipt\"></i></span>
                                </button>
                            </div>
                            
                            <div id=\"error-message\" role=\"alert\"></div>
                            
                            <div class=\"alert alert-info p-2 text-center mt-3 mb-0\" role=\"alert\" style=\"font-size:11px!important;\">
                                <span class=\"alert-inner--text\">Para pagos en efectivo <strong>es importante</strong> que después de imprimir el vale cierres la ventana emergente para que los datos puedan ser asociados a tu cuenta.</span>
                            </div>
                            
                        </form>
                    
                    </div>
                    
                ";
        }
        // line 151
        echo "            
            </div>
        </div>
        
        <div class=\"d-block d-lg-none mt-3 text-right\">
            <a href=\"compra\" class=\"btn btn-sm btn-icon boton_negro\">
                <i class=\"fas fa-chevron-left\"></i>
                <span class=\"btn-inner--text\">Modificar compra</span>
            </a>
        </div>
    </main>
    
";
    }

    // line 165
    public function block_appFooter($context, array $blocks = array())
    {
        // line 166
        echo "    <script src=\"./assets/jscontrollers/compra/procesar.js\"></script>
    
    <script src=\"https://js.stripe.com/v3/\"></script>
    <script src=\"./assets/jscontrollers/compra/stripe.js\"></script>
    
";
    }

    public function getTemplateName()
    {
        return "compra/procesar.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  225 => 166,  222 => 165,  206 => 151,  151 => 98,  147 => 96,  141 => 94,  139 => 93,  127 => 84,  114 => 73,  112 => 68,  98 => 56,  96 => 55,  67 => 28,  64 => 27,  42 => 8,  39 => 7,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appTitle %}
    <title>Mi compra</title>
{% endblock %}

{% block appHeader %}
<style>
.table-cards tr.bg-white>th:after,
.table-cards tr.bg-white>td:after {
    border-top: 1px solid var(--borderColor);
    border-bottom: 1px solid var(--borderColor);
}
.table-hover tr.bg-white:hover {
    background-color: #fff!important;
}
.table-hover tr.bg-white:hover>th,
.table-hover tr.bg-white:hover>td{
    background-color: #f8f9fa!important;
}
.table-cards tr.bg-white.empty>th:after{
    border:none!important;
}
</style>
<link rel=\"stylesheet\" href=\"./assets/plantilla/stripe.css\">
{% endblock %}
{% block appBody %}

    <main class=\"container px-3 px-md-0 mt-2\">

        <div class=\"row px-0 align-items-center destacados\">
            <div class=\"col-5 col-md-7 col-lg-9\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><small class=\"font-weight-bold\"><span class=\"resultados\"></span></small></p>
                    <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-shopping-bag\"></i> Compra</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-7 col-md-5 col-lg-3 text-right\">
                <a href=\"compra\" class=\"btn btn-sm btn-icon boton_negro\">
                    <i class=\"fas fa-chevron-left\"></i>
                    <span class=\"btn-inner--text\">Modificar compra</span>
                </a>
            </div>
        </div>
        
        <div class=\"row px-0 mt-3\">
            <div class=\"col-12 col-lg-8 order-12 order-lg-1\">
                
                <div class=\"tabla_carrito d-block\">
                    
                </div>
                
            </div>
            <div class=\"col-12 col-lg-4 d-none order-1 order-lg-12 mb-4 mb-lg-0 div_procesar_pago\">
                
                {% if telefono == '' or cp == ''%}
                    <div class=\"alert bg-light\" role=\"alert\">
                        <h4 class=\"alert-heading\">¡Acción necesaria!</h4>
                        <p>Con la finalidad de agilizar el proceso de tu compra, completa los siguientes datos:</p>
                        <ul>
                            <li>Número de teléfono</li>
                            <li>Dirección de envió</li>
                        </ul>
                        <p>Es necesario incluso si la entrega es en sucursal.</p>
                        <hr>
                        <p class=\"mb-0\">Entra a tu perfil y después haz clic en la pestaña <a class=\"btn-link font-weight-bold\" href=\"cuenta/mis-datos\">Mis datos</a>.</p>
                    </div>
                {% else %}
                    {#
                    <div class=\"text-center\">
                        <img src=\"./assets/plantilla/img/general/stripe.svg\" width=\"30%\" class=\"mt-0 mb-4\">
                    </div>
                    #}
                    
                    <div class=\"p-4 border rounded text-center z-depth-1\">
                        <table class=\"w-100 border-bottom\">
                            <tr>
                                <td class=\"w-50\">Subtotal <span class=\"subtotal\" style=\"font-weight:normal;\">\$ 0.00</span></td>
                                <td><span class=\"envio\" style=\"font-weight:normal;\">--</span></td>
                            </tr>
                        </table>

                        <h1 class=\"mb-4 p-0 text-app\"><span class=\"total\"></span></h1>
                        
                        <form action=\"{{url_post}}\" method=\"post\" id=\"payment-form\">
                            <div class=\"form-group\">
                                <label for=\"card-element\">
                                    Paga con tarjeta de crédito o débito
                                </label>
                                <div id=\"card-element\">
                                    <!-- A Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors. -->
                                {% if message != '' %}
                                    <div id=\"card-errors\" role=\"alert\">{{message}}</div>
                                {% else %}
                                    <div id=\"card-errors\" role=\"alert\"></div>
                                {% endif %}
                            </div>
                            <div class=\"text-center div_pagar_tarjeta\">
                                <button class=\"btn btn-sm btn-icon boton_color\">
                                    <span class=\"btn-inner--text\">Procesar pago</span>
                                    <span class=\"btn-inner--icon\"><i class=\"far fa-credit-card\"></i></span>
                                </button>
                            </div>
                        </form>
                        <div class=\"cargando_ d-none\">
                            <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <hr class=\"mt-4\">
                        
                        <label>
                            Paga en efectivo con <img src=\"./assets/plantilla/img/general/oxxo.svg\" width=\"35px\">
                        </label>
                        <div class=\"text-center div_solicitar_vale\">
                            <button class=\"btn btn-sm btn-icon boton_color\">
                                <span class=\"btn-inner--text\">Solicitar vale</span>
                                <span class=\"btn-inner--icon\"><i class=\"fas fa-receipt\"></i></span>
                            </button>
                        </div>
                        
                        <div class=\"cargando d-none\">
                            <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                        </div>
                        
                        <form id=\"payment-form-oxxo\" class=\"d-none\">
                                
                            <input type=\"hidden\" id=\"name\" name=\"name\" value=\"\">
                            <input type=\"hidden\" id=\"email\" name=\"email\" value=\"\">
                            <input type=\"hidden\" id=\"intent\" name=\"intent\" value=\"\">
                            <input type=\"hidden\" id=\"id_stripe\" name=\"id_stripe\" value=\"\">

                            <div class=\"text-center div_imprimir_vale\">
                                <button id=\"submit-button\" class=\"btn btn-sm btn-icon boton_color\">
                                    <span class=\"btn-inner--text\">Imprimir vale</span>
                                    <span class=\"btn-inner--icon\"><i class=\"fas fa-receipt\"></i></span>
                                </button>
                            </div>
                            
                            <div id=\"error-message\" role=\"alert\"></div>
                            
                            <div class=\"alert alert-info p-2 text-center mt-3 mb-0\" role=\"alert\" style=\"font-size:11px!important;\">
                                <span class=\"alert-inner--text\">Para pagos en efectivo <strong>es importante</strong> que después de imprimir el vale cierres la ventana emergente para que los datos puedan ser asociados a tu cuenta.</span>
                            </div>
                            
                        </form>
                    
                    </div>
                    
                {% endif %}
            
            </div>
        </div>
        
        <div class=\"d-block d-lg-none mt-3 text-right\">
            <a href=\"compra\" class=\"btn btn-sm btn-icon boton_negro\">
                <i class=\"fas fa-chevron-left\"></i>
                <span class=\"btn-inner--text\">Modificar compra</span>
            </a>
        </div>
    </main>
    
{% endblock %}

{% block appFooter %}
    <script src=\"./assets/jscontrollers/compra/procesar.js\"></script>
    
    <script src=\"https://js.stripe.com/v3/\"></script>
    <script src=\"./assets/jscontrollers/compra/stripe.js\"></script>
    
{% endblock %}", "compra/procesar.twig", "/home4/eltimonl/public_html/app/templates/compra/procesar.twig");
    }
}
