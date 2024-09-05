<?php

/* busquedAvanzada/formulario.twig */
class __TwigTemplate_433608e287133ad9de818131c93af28a30f3daa95c400803bff2263a2f62742c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "busquedAvanzada/formulario.twig", 1);
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
    <main class=\"container mt-2 destacados mb-5\">
        
        <div class=\"container destacados p-0\">
            
            <p class=\"m-0 p-0 text-muted font-weight-bold\"><small class=\"font-weight-bold\">Búsqueda</small></p>
            <h1 class=\"m-0 p-0 titulo_destacado\"><i class=\"fas fa-search\"></i> Avanzada</h1>
            <div class=\"my-1 mb-4 separador separador_color\"></div>
            
            <div class=\"row justify-content-md-center\">
                <div class=\"col-12 col-md-6\">
                    <div class=\"card z-depth-3\">
                        <div class=\"card-header\">
                            <div class=\"row align-items-center\">
                                <div class=\"col-8\">
                                    <h4 class=\"heading h5 mb-0\">Datos de búsqueda</h4>
                                </div>
                                <div class=\"col-4\">
                                    <div class=\"card-icon-actions text-right\">
                                        <i class=\"fas fa-search\"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=\"card-body\">
                            <p class=\"card-text\">
                                <p>Utiliza el siguiente formulario para realizar tus búsquedas de una forma más precisa, solo llena los campos de acuerdo a lo que deseas buscar.</p>
                                <form role=\"form\" id=\"form_search\" action=\"busquedAvanzada/\" method=\"GET\">
                                    <input type=\"hidden\" name=\"s_search\" value=\"true\">
                                    <div class=\"form-group\">
                                        <div class=\"input-group input-group-transparent mb-2\">
                                            <input type=\"text\" name=\"s_isbn\" id='s_isbn' class=\"form-control form-control-sm\" placeholder=\"ISBN o código de berras\" autocomplete=\"off\">
                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    <i class=\"fas fa-barcode\"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <div class=\"input-group input-group-transparent mb-2\">
                                            <input type=\"text\" name=\"s_title\" id='s_title' class=\"form-control form-control-sm\" placeholder=\"Título de la obra\" autocomplete=\"off\">
                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    <i class=\"fas fa-book\"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <div class=\"input-group input-group-transparent mb-2\">
                                            <input type=\"text\" name=\"s_author\" id=\"s_author\" class=\"form-control form-control-sm\" placeholder=\"Autor de la obra\" autocomplete=\"off\">
                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    <i class=\"fas fa-user-tag\"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <div class=\"input-group input-group-transparent mb-2\">
                                            <input type=\"text\" name=\"s_publishing\" id=\"s_publishing\" class=\"form-control form-control-sm\" placeholder=\"Sello editorial\" autocomplete=\"off\">
                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    <i class=\"fas fa-university\"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=\"form-group\">
                                        <div class=\"input-group input-group-transparent mb-2\">
                                            <input type=\"text\" name=\"s_category\" id=\"s_category\" class=\"form-control form-control-sm\" placeholder=\"Categoría o materia\" autocomplete=\"off\">
                                            <div class=\"input-group-append\">
                                                <span class=\"input-group-text\">
                                                    <i class=\"fas fa-tags\"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </p>
                        </div>
                        <div class=\"card-footer\">
                            <div class=\"row align-items-center\">
                                <div class=\"col-12 text-right\">
                                    <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_search\">
                                        <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                        <span class=\"btn-inner--text\">Realizar búsqueda</span>
                                        <span class=\"btn-inner--icon\"><i class=\"fas fa-check\"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>
    
";
    }

    // line 118
    public function block_appFooter($context, array $blocks = array())
    {
        // line 119
        echo "    <script src=\"assets/jscontrollers/busquedAvanzada/busqueda.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "busquedAvanzada/formulario.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  193 => 119,  190 => 118,  85 => 15,  82 => 14,  77 => 12,  72 => 11,  67 => 10,  62 => 9,  57 => 8,  52 => 7,  47 => 6,  40 => 4,  37 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "busquedAvanzada/formulario.twig", "/home4/eltimonl/public_html/app/templates/busquedAvanzada/formulario.twig");
    }
}
