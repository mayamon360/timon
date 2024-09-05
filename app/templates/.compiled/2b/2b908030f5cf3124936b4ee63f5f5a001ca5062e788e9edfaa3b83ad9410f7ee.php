<?php

/* compra/compra.twig */
class __TwigTemplate_aa6d20426dd833b82d948223f7d4b21e680ea1a4ba3867a19e47ece025a801dc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "compra/compra.twig", 1);
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
        echo "    <title>Mi cesta</title>
";
    }

    // line 7
    public function block_appHeader($context, array $blocks = array())
    {
        // line 8
        echo "<style>
    .icon i,.icon svg{
        font-size:12px;
    }
</style>
";
    }

    // line 14
    public function block_appBody($context, array $blocks = array())
    {
        // line 15
        echo "
    <main class=\"container px-3 px-md-0 mt-2\">

        <div class=\"row align-items-center destacados\">
            
            <div class=\"col-5 col-md-7 col-lg-9\">
                <p class=\"m-0 p-0 text-muted animated fadeIn\"><small class=\"font-weight-bold\"><span class=\"resultados\"></span></small></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-shopping-bag\"></i> Mi cesta</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
\t\t\t\t
\t\t\t\t";
        // line 29
        echo "            </div>
            
            <div class=\"col-7 col-md-5 col-lg-3 text-right div_procesar_compra\"></div>
            
        </div>
        
        
        
        ";
        // line 37
        if ((($context["error_get"] ?? null) == "cambio_stock")) {
            // line 38
            echo "            <div class=\"alert alert-danger alert-dismissible fade show mt-3 animated flash\" role=\"alert\">
                <span class=\"alert-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                <span class=\"alert-inner--text\">Hubo cambios en su lista de compra, por favor revise antes de continuar con su compra.</span>
            </div>
        ";
        } elseif ((        // line 42
($context["error_get"] ?? null) == "compra_vacia")) {
            // line 43
            echo "            <div class=\"alert alert-danger alert-dismissible fade show mt-3 animated flash\" role=\"alert\">
                <span class=\"alert-inner--icon\"><i class=\"fas fa-info-circle\"></i></span>
                <span class=\"alert-inner--text\">Agrega productos a la lista para poder procesar el pago.</span>
            </div>
        ";
        }
        // line 48
        echo "        
        <div class=\"row px-0 mt-3\">
            <div class=\"d-none d-md-inline-block col-12 col-lg-8 order-12 order-lg-1 tabla_carrito\"></div>
            <div class=\"d-inline-block d-md-none col-12 col-lg-8 order-12 order-lg-1 div_carrito_movil\"></div>
            <div class=\"col-12 col-lg-4 mb-4 mt-lg-0 order-1 order-lg-12\">
                <div class=\"p-4 border rounded z-depth-1\">
                    <div class=\"m-0 p-0 informacion_compra\">
                        <h6 class=\"text-muted font-weight-regular\">Subtotal</h6>
                        <h3 class=\"p-0 m-0\">
                            <span class=\"subtotal\" style=\"font-weight:normal;\">\$ 0.00</span>
                        </h3>
                        <hr>
                        <h6 class=\"text-muted font-weight-regular\">Opciones de envío</h6>
                        <p class=\"p-0 m-0 font-weight-700 w-100 mt-2\">
                            <div class=\"opc_envio\">
                                --
                            </div>
                            <div class=\"info_dir\"></div>
                        </p>
                        <hr>
                        <h6 class=\"text-muted font-weight-regular\">Total</h6>
                        <h1 class=\"text-app m-0\">
                            <label class=\"total m-0\">\$ 0.00</label>
                        </h1>
                    </div>
                    <div class=\"text-center d-none cargando\">
                        <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class=\"text-right d-block d-lg-none mt-3 mb-4 div_procesar_compra\"></div>
            
    </main>
    
";
    }

    // line 86
    public function block_appFooter($context, array $blocks = array())
    {
        // line 87
        echo "    <script src=\"assets/plugins/Inputmask-5.x/jquery.inputmask.min.js\"></script>
\t<script src=\"assets/jscontrollers/compra/compra.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "compra/compra.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  136 => 87,  133 => 86,  93 => 48,  86 => 43,  84 => 42,  78 => 38,  76 => 37,  66 => 29,  54 => 15,  51 => 14,  42 => 8,  39 => 7,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "compra/compra.twig", "/home4/eltimonl/public_html/app/templates/compra/compra.twig");
    }
}
