<?php

/* busquedAvanzada/resultados.twig */
class __TwigTemplate_170638b7713ef20e829776ad5e6e46997b7ce4dc973a5a4e776a34480c942893 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "busquedAvanzada/resultados.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'ogTitle' => array($this, 'block_ogTitle'),
            'ogUrl' => array($this, 'block_ogUrl'),
            'ogdescription' => array($this, 'block_ogdescription'),
            'ogImage' => array($this, 'block_ogImage'),
            'ogType' => array($this, 'block_ogType'),
            'ogSiteName' => array($this, 'block_ogSiteName'),
            'ogLocale' => array($this, 'block_ogLocale'),
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
        echo "    <title>Resultados de búsqueda avanzada</title>
";
    }

    // line 6
    public function block_ogTitle($context, array $blocks = array())
    {
    }

    // line 7
    public function block_ogUrl($context, array $blocks = array())
    {
    }

    // line 8
    public function block_ogdescription($context, array $blocks = array())
    {
    }

    // line 9
    public function block_ogImage($context, array $blocks = array())
    {
    }

    // line 10
    public function block_ogType($context, array $blocks = array())
    {
    }

    // line 11
    public function block_ogSiteName($context, array $blocks = array())
    {
    }

    // line 12
    public function block_ogLocale($context, array $blocks = array())
    {
    }

    // line 14
    public function block_appBody($context, array $blocks = array())
    {
        // line 15
        echo "
    <main class=\"container mt-2 destacados mb-5\">
        <input type=\"hidden\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["isbn"] ?? null), "html", null, true);
        echo "\" id=\"isbn\">
        <input type=\"hidden\" value=\"";
        // line 18
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "\" id=\"title\">
        <input type=\"hidden\" value=\"";
        // line 19
        echo twig_escape_filter($this->env, ($context["author"] ?? null), "html", null, true);
        echo "\" id=\"author\">
        <input type=\"hidden\" value=\"";
        // line 20
        echo twig_escape_filter($this->env, ($context["publishing"] ?? null), "html", null, true);
        echo "\" id=\"publishing\">
        <input type=\"hidden\" value=\"";
        // line 21
        echo twig_escape_filter($this->env, ($context["category"] ?? null), "html", null, true);
        echo "\" id=\"category\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn text-truncate titulo_destacado\"><i class=\"fas fa-chevron-circle-right\"></i> ";
        // line 25
        echo twig_escape_filter($this->env, ($context["isbn"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["author"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["publishing"] ?? null), "html", null, true);
        echo " ";
        echo twig_escape_filter($this->env, ($context["category"] ?? null), "html", null, true);
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

    // line 69
    public function block_appFooter($context, array $blocks = array())
    {
        // line 70
        echo "    <script src=\"assets/jscontrollers/busquedAvanzada/resultados.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "busquedAvanzada/resultados.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  168 => 70,  165 => 69,  110 => 25,  103 => 21,  99 => 20,  95 => 19,  91 => 18,  87 => 17,  83 => 15,  80 => 14,  75 => 12,  70 => 11,  65 => 10,  60 => 9,  55 => 8,  50 => 7,  45 => 6,  40 => 4,  37 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "busquedAvanzada/resultados.twig", "/home4/eltimonl/public_html/app/templates/busquedAvanzada/resultados.twig");
    }
}
