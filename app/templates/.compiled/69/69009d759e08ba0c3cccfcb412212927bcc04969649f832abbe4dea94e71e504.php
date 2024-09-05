<?php

/* autenticacion/iniciar.twig */
class __TwigTemplate_7d2ffb5c9424f1bf871e40b3869643590c78c8614169d94ea0b9ee6f102d32c1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 div_form_login\">
    <div class=\"card bg-white border animated fadeIn z-depth-2\">
        <div class=\"card-body\">
            <h4 class=\"heading h4 pt-1 pb-4\">
                Ingresa a tu cuenta
            </h4>
            <form role=\"form\" id=\"form_login\">
                <div class=\"form-group\">
                    <div class=\"input-group input-group-transparent mb-2\">
                        <input type=\"email\" name=\"l_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                        <div class=\"input-group-append\">
                            <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                        </div>
                    </div>
                </div>
                <div class=\"form-group\">
                    <div class=\"input-group input-group-transparent mb-2\">
                        <input type=\"password\" name=\"l_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
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
                <div class=\"text-right\">
                    <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_login\" disabled>
                        <span class=\"btn-inner--text\">Iniciar sesión</span>
                        <i class=\"fas fa-sign-in-alt\"></i>
                    </button>
                </div>
                <div class=\"text-center mt-4\">
                    <a href=\"#\" class=\"forgot_link\">¿Has olvidado tu contraseña?</a>
                </div>
            </form>
        </div>
        <div class=\"card-footer text-center\">
            <span class=\"text-muted\">¿Eres nuevo?</span> <a href=\"";
        // line 43
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion/registro\">Regístrate</a>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "autenticacion/iniciar.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  63 => 43,  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"col-12 col-sm-10 offset-sm-1 col-md-6 offset-md-3 col-lg-4 offset-lg-4 div_form_login\">
    <div class=\"card bg-white border animated fadeIn z-depth-2\">
        <div class=\"card-body\">
            <h4 class=\"heading h4 pt-1 pb-4\">
                Ingresa a tu cuenta
            </h4>
            <form role=\"form\" id=\"form_login\">
                <div class=\"form-group\">
                    <div class=\"input-group input-group-transparent mb-2\">
                        <input type=\"email\" name=\"l_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                        <div class=\"input-group-append\">
                            <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                        </div>
                    </div>
                </div>
                <div class=\"form-group\">
                    <div class=\"input-group input-group-transparent mb-2\">
                        <input type=\"password\" name=\"l_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
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
                <div class=\"text-right\">
                    <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_login\" disabled>
                        <span class=\"btn-inner--text\">Iniciar sesión</span>
                        <i class=\"fas fa-sign-in-alt\"></i>
                    </button>
                </div>
                <div class=\"text-center mt-4\">
                    <a href=\"#\" class=\"forgot_link\">¿Has olvidado tu contraseña?</a>
                </div>
            </form>
        </div>
        <div class=\"card-footer text-center\">
            <span class=\"text-muted\">¿Eres nuevo?</span> <a href=\"{{config.build.url}}autenticacion/registro\">Regístrate</a>
        </div>
    </div>
</div>", "autenticacion/iniciar.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\autenticacion\\iniciar.twig");
    }
}
