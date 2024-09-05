<?php

/* destacados/mas-vendidos.twig */
class __TwigTemplate_574655a03fe08d9b29c6be68fcb934272babc4bb90fc7e929578325e0e91b691 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "destacados/mas-vendidos.twig", 1);
        $this->blocks = array(
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

    // line 3
    public function block_appTitle($context, array $blocks = array())
    {
        // line 4
        echo "    <title>Los más vendidos</title>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    <main class=\"container mt-2 destacados\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-8 col-sm-6 col-md-7 col-lg-9\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más vendidos</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-4 col-sm-6 col-md-5 col-lg-3 text-right\">
                <div class=\"input-group input-group-sm mb-0 animated fadeIn my-auto\">
                    <div class=\"input-group-prepend d-none d-sm-flex\">
                        <label class=\"input-group-text text-uppercase py-1\" for=\"mostar_resultados\"><small>Mostrar</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"mostar_resultados\">
                        <option value=\"25\">25</option>
                        <option value=\"50\">50</option>
                        <option value=\"100\">100</option>
                    </select>
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase border-left-0 rounded-right d-none d-sm-flex py-1\" for=\"mostar_resultados\"><small>Registros</small></label>
                    </div>
                </div>
            </div>

            <div class=\"col-12 mt-2\">
                <div class=\"row\" id=\"resultados\">
                    
                </div>
            </div>

            <div class=\"col-12 mt-5\">
                <div class=\"text-center div_cargando\">
                    <button type=\"button\" class=\"btn btn-sm boton_negro\" id=\"boton_cargar_mas\">
                        <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                        Mostrar más resultados
                    </button>
                    <p class=\"text-muted d-none animated flash div_cargando_texto\"></p>
                </div>
            </div>

        </div>
    </main>
    
";
    }

    // line 53
    public function block_appFooter($context, array $blocks = array())
    {
        // line 54
        echo "\t<script src=\"assets/jscontrollers/destacados/mas-vendidos.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "destacados/mas-vendidos.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 54,  88 => 53,  41 => 8,  38 => 7,  33 => 4,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "destacados/mas-vendidos.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\destacados\\mas-vendidos.twig");
    }
}
