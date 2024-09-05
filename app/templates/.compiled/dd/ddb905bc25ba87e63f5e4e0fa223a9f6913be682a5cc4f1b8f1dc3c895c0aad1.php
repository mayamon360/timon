<?php

/* autenticacion/autenticacion.twig */
class __TwigTemplate_52c3dd8d258225c41c2b8e0b269883df302884a87ec346a34ee69661a8d2ec68 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "autenticacion/autenticacion.twig", 1);
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
        echo "<script src=\"https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit\"
        async defer>
</script>
";
    }

    // line 7
    public function block_appBody($context, array $blocks = array())
    {
        // line 8
        echo "
    ";
        // line 9
        echo ($context["activar_cuenta"] ?? null);
        echo "
    <main class=\"container mt-5\">

        <div class=\"row\">
        
            <div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 div_form_login\">
                <div class=\"card bg-white border animated fadeIn z-depth-2\">
                    <div class=\"card-body\">
                        <h4 class=\"heading h4 pt-1 pb-4\">
                            Ingresa a tu cuenta
                        </h4>
                        <form role=\"form\" id=\"form_login\">
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" name=\"l_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\" autocomplete=\"off\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"l_password\" id=\"l_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
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
                            <div class=\"col-xs-12 my-4 text-center\">
\t\t\t                    <div id=\"g-recaptcha\" class=\"text-center\"></div>
\t\t\t                </div>
                            <div class=\"text-right\">
                                <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_login\" disabled>
                                    <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                    <span class=\"btn-inner--text\">Iniciar sesión</span>
                                    <i class=\"fas fa-sign-in-alt\"></i>
                                </button>
                            </div>
                            <div class=\"text-center mt-4\">
                                <a href=\"";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion/recuperar\">¿Has olvidado tu contraseña?</a>
                            </div>
                        </form>
                    </div>
                    <div class=\"card-footer text-center\">
                        <span class=\"text-muted\">¿Eres nuevo?</span> <a href=\"";
        // line 60
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion/registro\">Regístrate</a>
                    </div>
                </div>
            </div>
            
        </div>

    </main>

";
    }

    // line 71
    public function block_appFooter($context, array $blocks = array())
    {
        // line 72
        echo "\t<script src=\"assets/jscontrollers/autenticacion/autenticacion.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "autenticacion/autenticacion.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  120 => 72,  117 => 71,  103 => 60,  95 => 55,  46 => 9,  43 => 8,  40 => 7,  33 => 3,  30 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "autenticacion/autenticacion.twig", "/home4/eltimonl/public_html/app/templates/autenticacion/autenticacion.twig");
    }
}
