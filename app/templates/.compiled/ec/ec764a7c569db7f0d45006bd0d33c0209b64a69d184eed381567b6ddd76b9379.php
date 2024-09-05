<?php

/* autenticacion/recuperar.twig */
class __TwigTemplate_5d92498e266ffdb37e1d05fbde1e5ad17cdc5cb4a4a841450b13e0dd24d78cee extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "autenticacion/recuperar.twig", 1);
        $this->blocks = array(
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
    public function block_appBody($context, array $blocks = array())
    {
        // line 3
        echo "
    ";
        // line 4
        echo ($context["change_password"] ?? null);
        echo "
    <main class=\"container mt-5\">

        <div class=\"row\">

            <div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 div_form_forgot\">
                <div class=\"card bg-white border animated fadeIn z-depth-2\">
                    <div class=\"card-body\">
                        <h4 class=\"heading h4 pt-1 pb-4\">Solicitar contraseña
                        </h4>
                        <form role=\"form\" id=\"form_forgot\">
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" name=\"f_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <small class=\"form-text text-muted mb-3\">Ingresa la dirección de correo electrónico asociada a tu cuenta y te enviaremos un correo con las instrucciones para obtener una nueva contraseña.</small>
                            <div class=\"text-right\">
                                <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_forgot\" disabled>
                                    <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                    <span class=\"btn-inner--text\">Enviar solicitud</span>
                                    <i class=\"fas fa-paper-plane\"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class=\"card-footer text-center\">
                        <span class=\"text-muted\">¿Tienes cuenta?</span> <a href=\"";
        // line 34
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion\">Iniciar sesión</a><br><br>
                        <span class=\"text-muted\">¿Eres nuevo?</span> <a href=\"";
        // line 35
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion/registro\">Regístrate</a>
                    </div>
                </div>
            </div>

        </div>

    </main>

";
    }

    // line 46
    public function block_appFooter($context, array $blocks = array())
    {
        // line 47
        echo "\t<script src=\"assets/jscontrollers/autenticacion/recuperar.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "autenticacion/recuperar.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 47,  86 => 46,  72 => 35,  68 => 34,  35 => 4,  32 => 3,  29 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "autenticacion/recuperar.twig", "/home4/eltimonl/public_html/app/templates/autenticacion/recuperar.twig");
    }
}
