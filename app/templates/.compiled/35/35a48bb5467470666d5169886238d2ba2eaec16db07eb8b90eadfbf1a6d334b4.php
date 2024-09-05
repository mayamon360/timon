<?php

/* editorial/editorial.twig */
class __TwigTemplate_e59d92b70a5300841fe7e2c5096e5182d405cdde809560fdc8d44e027a7bc2e7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "editorial/editorial.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'appDescription' => array($this, 'block_appDescription'),
            'appKeyword' => array($this, 'block_appKeyword'),
            'appImage' => array($this, 'block_appImage'),
            'ogTitle' => array($this, 'block_ogTitle'),
            'ogUrl' => array($this, 'block_ogUrl'),
            'ogdescription' => array($this, 'block_ogdescription'),
            'ogImage' => array($this, 'block_ogImage'),
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
        echo "    <title>Libros de ";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "</title>
    <meta name=\"title\" content=\"Libros de ";
        // line 5
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "\">
";
    }

    // line 7
    public function block_appDescription($context, array $blocks = array())
    {
        // line 8
        echo "    <meta name=\"description\" content=\"Libros de ";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "\">
";
    }

    // line 10
    public function block_appKeyword($context, array $blocks = array())
    {
        // line 11
        echo "    <meta name=\"keyword\" content=\"Libros de ";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo ",";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "\"> 
";
    }

    // line 13
    public function block_appImage($context, array $blocks = array())
    {
    }

    // line 14
    public function block_ogTitle($context, array $blocks = array())
    {
        echo "  
    <meta property=\"og:title\" content=\"Libros de ";
        // line 15
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "\">
";
    }

    // line 17
    public function block_ogUrl($context, array $blocks = array())
    {
        // line 18
        echo "    <meta property=\"og:url\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "editorial/";
        echo twig_escape_filter($this->env, ($context["metodo"] ?? null), "html", null, true);
        echo "\">
";
    }

    // line 20
    public function block_ogdescription($context, array $blocks = array())
    {
        // line 21
        echo "    <meta property=\"og:description\" content=\"Libros de ";
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "\">
";
    }

    // line 23
    public function block_ogImage($context, array $blocks = array())
    {
        // line 24
        echo "    <meta property=\"og:image\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "assets/plantilla/img/cabeceras/default/default.jpg\">
";
    }

    // line 27
    public function block_appBody($context, array $blocks = array())
    {
        // line 28
        echo "
    <main class=\"container mt-2 destacados mb-5\">
        <input type=\"hidden\" value=\"";
        // line 30
        echo twig_escape_filter($this->env, ($context["metodo"] ?? null), "html", null, true);
        echo "\" id=\"metodo\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-landmark\"></i> ";
        // line 34
        echo twig_escape_filter($this->env, ($context["editorial"] ?? null), "html", null, true);
        echo "</h1>
\t\t\t\t<div class=\"my-1 animated fadeIn separador separador_color\"></div>
            </div>
            <div class=\"col-12 col-sm-6 col-md-5 col-lg-4 text-right pt-3 pt-md-0\">
                <div class=\"input-group input-group-sm mb-0 animated fadeIn my-auto\">
                    <div class=\"input-group-prepend\">
                        <label class=\"input-group-text text-uppercase py-1\" for=\"ordenar_resultados\"><small>Ordenar por</small></label>
                    </div>
                    <select class=\"custom-select custom-select-sm\" id=\"ordenar_resultados\">
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

    // line 78
    public function block_appFooter($context, array $blocks = array())
    {
        // line 79
        echo "    <script src=\"assets/jscontrollers/editorial/editorial.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "editorial/editorial.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  185 => 79,  182 => 78,  135 => 34,  128 => 30,  124 => 28,  121 => 27,  114 => 24,  111 => 23,  104 => 21,  101 => 20,  92 => 18,  89 => 17,  83 => 15,  78 => 14,  73 => 13,  64 => 11,  61 => 10,  54 => 8,  51 => 7,  45 => 5,  40 => 4,  37 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "editorial/editorial.twig", "/home4/eltimonl/public_html/app/templates/editorial/editorial.twig");
    }
}
