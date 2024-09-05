<?php

/* cuenta/cambiar-contrasena.twig */
class __TwigTemplate_2dfd4196929470d2cb1923ffc1278189195d5942fb91f0b187061729e117c764 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "cuenta/cambiar-contrasena.twig", 1);
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
        echo "    <title>Cambiar contraseña</title>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    ";
        // line 9
        $this->loadTemplate("cuenta/menu", "cuenta/cambiar-contrasena.twig", 9)->display($context);
        // line 10
        echo "
    <main class=\"container mt-4\">
        <div class=\"row\">
            <div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4\">
                <div class=\"card bg-white border animated fadeIn z-depth-2\">
                    <form role=\"form\" id=\"form_change_password\">
                        <div class=\"card-body\">
                            <h4 class=\"heading h4 pt-1 pb-4\">
                                Cambiar contraseña
                            </h4>

                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"a_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña actual\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"n_password\" id=\"n_password\" class=\"form-control form-control-sm\" placeholder=\"Nueva contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\" id=\"mostrar_contraseña\">
                                        <span class=\"input-group-text rounded-right\">
                                            <i class=\"fas fa-eye-slash\"></i>
                                        </span>
                                    </div>
                                    <div class=\"input-group-append d-none\" style=\"cursor:pointer;\" id=\"ocultar_contraseña\">
                                        <span class=\"input-group-text rounded-right\">
                                            <i class=\"fas fa-eye\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_password\" class=\"form-control form-control-sm\" placeholder=\"Repetir nueva contraseña\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class=\"progress-wrapper mt-4\">
                                <h4 class=\"progress-label w-100 text-center text-muted\">Análisis de seguridad</h4>
                                <div class=\"progress\" style=\"height: 5px;\">
                                    <div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 0%;\"></div>
                                </div>
                            </div>
                        </div>
                        <div class=\"card-footer text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_change_password\" disabled>
                                <span class=\"btn-inner--text\">Cambiar contraseña</span>
                                <i class=\"fas fa-check\"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </main>

";
    }

    // line 81
    public function block_appFooter($context, array $blocks = array())
    {
        // line 82
        echo "    <script src=\"assets/jscontrollers/cuenta/cambiar-contrasena.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "cuenta/cambiar-contrasena.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 82,  119 => 81,  46 => 10,  44 => 9,  41 => 8,  38 => 7,  33 => 4,  30 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "cuenta/cambiar-contrasena.twig", "/home4/eltimonl/public_html/app/templates/cuenta/cambiar-contrasena.twig");
    }
}
