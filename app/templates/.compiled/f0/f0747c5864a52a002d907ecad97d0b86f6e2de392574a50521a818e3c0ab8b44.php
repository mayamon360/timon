<?php

/* autenticacion/registro.twig */
class __TwigTemplate_14a37c429769187ceda7810f6abf19153ab66d4e9c72464a2f02856a2c34d7a6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "autenticacion/registro.twig", 1);
        $this->blocks = array(
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

    // line 2
    public function block_appHeader($context, array $blocks = array())
    {
        // line 3
        echo "<!--<script src=\"https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit\"
        async defer>
</script>-->
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    <main class=\"container mt-5\">

        <div class=\"row\">

            <div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 div_form_register\">
                <div class=\"card bg-white border animated fadeIn z-depth-2\">
                    <div class=\"card-body\">
                        <h4 class=\"heading h4 pt-1 pb-4\">
                            Regístrate
                        </h4>
                        <form id=\"form_register\">
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"text\" name=\"r_name\" id=\"r_name\" class=\"form-control form-control-sm\" placeholder=\"Nombre completo\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" name=\"r_mail\" id=\"r_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_password\" id=\"r_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_repassword\" id=\"r_repassword\" class=\"form-control form-control-sm\" placeholder=\"Confirmar contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!--<div class=\"col-xs-12 my-4 text-center\">
\t\t\t                    <div id=\"g-recaptcha\" style=\"margin:auto;\"></div>
\t\t\t                </div>-->
\t\t\t    
                            <div class=\"text-right\">
                                <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_register\" disabled>
                                    <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                    <span class=\"btn-inner--text\">Registrarme</span>
                                    <i class=\"fas fa-check\"></i>
                                </button>
                            </div>
\t\t\t    
                        </form>
                    </div>
                    <div class=\"card-footer text-center\">
                        <span class=\"text-muted\">¿Tienes cuenta?</span> <a href=\"";
        // line 72
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion\">Iniciar sesión</a>
                    </div>
                </div>
                <br><br>
                <h5 class=\"m-0 p-0 mb-2\">¿Cómo me registro?</h5>
                <video src=\"./assets/videos/Crear_cuenta.mp4\" width=\"100%\" controls></video>
                <br><br>
                
            </div>

        </div>

    </main>
    
";
    }

    // line 88
    public function block_appFooter($context, array $blocks = array())
    {
        // line 89
        echo "    <script src=\"assets/jscontrollers/autenticacion/registro.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "autenticacion/registro.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 89,  128 => 88,  109 => 72,  43 => 8,  40 => 7,  33 => 3,  30 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "autenticacion/registro.twig", "/home4/eltimonl/public_html/app/templates/autenticacion/registro.twig");
    }
}
