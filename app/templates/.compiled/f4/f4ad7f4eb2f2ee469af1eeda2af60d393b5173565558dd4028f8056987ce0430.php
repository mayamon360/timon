<?php

/* busqueda/busqueda.twig */
class __TwigTemplate_96e17c15de68f4b122e0ab74888b3827fb7eca1a61d3399e1fbeae0a28463c54 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "busqueda/busqueda.twig", 1);
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
        echo "    <title>Resultados de ";
        echo twig_escape_filter($this->env, ($context["consulta"] ?? null), "html", null, true);
        echo "</title>
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
    <main class=\"container mt-2 destacados\">
        <input type=\"hidden\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, ($context["consulta"] ?? null), "html", null, true);
        echo "\" id=\"consulta\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn text-truncate titulo_destacado\"><i class=\"fas fa-chevron-circle-right\"></i> ";
        // line 21
        echo twig_escape_filter($this->env, ($context["consulta"] ?? null), "html", null, true);
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

    // line 65
    public function block_appFooter($context, array $blocks = array())
    {
        // line 66
        echo "    <script src=\"assets/jscontrollers/busqueda/busqueda.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "busqueda/busqueda.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  146 => 66,  143 => 65,  96 => 21,  89 => 17,  85 => 15,  82 => 14,  77 => 12,  72 => 11,  67 => 10,  62 => 9,  57 => 8,  52 => 7,  47 => 6,  40 => 4,  37 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}

{% block appTitle %}
    <title>Resultados de {{consulta}}</title>
{% endblock %}
{% block ogTitle %}{% endblock %}
{% block ogUrl %}{% endblock %}
{% block ogdescription %}{% endblock %}
{% block ogImage %}{% endblock %}
{% block ogType %}{% endblock %}
{% block ogSiteName %}{% endblock %}
{% block ogLocale %}{% endblock %}

{% block appBody %}

    <main class=\"container mt-2 destacados\">
        <input type=\"hidden\" value=\"{{consulta}}\" id=\"consulta\">
        <div class=\"row px-0 align-items-center\">
            <div class=\"col-12 col-sm-6 col-md-7 col-lg-8\">
                <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><span class=\"coincidencias\"></span></p>
                <h1 class=\"m-0 p-0 animated fadeIn text-truncate titulo_destacado\"><i class=\"fas fa-chevron-circle-right\"></i> {{consulta}}</h1>
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
    
{% endblock %}

{% block appFooter %}
    <script src=\"assets/jscontrollers/busqueda/busqueda.js\"></script>
{% endblock %}", "busqueda/busqueda.twig", "/home4/eltimonl/public_html/app/templates/busqueda/busqueda.twig");
    }
}
