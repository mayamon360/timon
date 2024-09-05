<?php

/* destacados/novedades.twig */
class __TwigTemplate_805e19600675b3a80c06192c4d95cd214dc741f33d32f14da5f37cd720c48774 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "destacados/novedades.twig", 1);
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
        echo "    <title>Los más nuevos</title>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    <main class=\"container mt-2 destacados\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-md-6 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-certificate\"></i> Los más nuevos</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-12 col-md-6 col-lg-4 text-right pt-3 pt-md-0\">
                <div class=\"input-group input-group-sm mb-0 animated fadeIn my-auto\">
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase py-1\" for=\"ordenar_resultados\"><small>Ordenar por</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"ordenar_resultados\">
                        <option value=\"novedad\">Más nuevo</option>
                        <option value=\"relevancia\">Relevancia</option>
                        <optgroup label=\"Nombre\">
                            <option value = \"nombre\">Nombre de la A-Z</option>
                            <option value = \"nombre_desc\">Nombre de la Z-A</option>
                        </optgroup>
                        <optgroup label=\"Precio\">
                            <option value = \"precio\">Precio de menor a mayor</option>
                            <option value = \"precio_desc\">Precio de mayor a menor</option>
                        </optgroup>

                    </select>
                </div>
            </div>

            <div class=\"col-12 mt-2\">
                <div class=\"row\" id=\"resultados\">
                    
                </div>
            </div>

            <div class=\"col-12 mt-5\">
                <div class=\"text-center d-none div_cargando\">
                    <span class=\"spinner-grow spinner-grow-sm bg-app\" role=\"status\" aria-hidden=\"true\"></span>
                    Procesando, por favor espere...
                </div>
            </div>

        </div>
    </main>
    
";
    }

    // line 55
    public function block_appFooter($context, array $blocks = array())
    {
        // line 56
        echo "\t<script src=\"assets/jscontrollers/destacados/novedades.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "destacados/novedades.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  93 => 56,  90 => 55,  41 => 8,  38 => 7,  33 => 4,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "destacados/novedades.twig", "/home4/eltimonl/public_html/app/templates/destacados/novedades.twig");
    }
}
