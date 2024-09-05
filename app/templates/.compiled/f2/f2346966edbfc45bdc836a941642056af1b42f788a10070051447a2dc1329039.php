<?php

/* autenticacion/registro.twig */
class __TwigTemplate_83c4089ff0ead51acbde07b539969b3ceec1195ea940b5a6001586b63924f95e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "autenticacion/registro.twig", 1);
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
                                    <input type=\"email\" name=\"r_name\" class=\"form-control form-control-sm\" placeholder=\"Nombre completo\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" name=\"r_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_repassword\" class=\"form-control form-control-sm\" placeholder=\"Confirmar contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"mt-4\">
                                <div class=\"custom-control custom-checkbox mb-3\">
                                    <input type=\"checkbox\" class=\"custom-control-input\" name=\"politicas\" id=\"politicas\" value=\"acepto\">
                                    <label class=\"custom-control-label\" for=\"politicas\" style=\"line-height:24px;\">
                                        He leído y acepto la <a href=\"";
        // line 55
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/privacidad\">política de privacidad</a>
                                    </label>
                                </div>
                            </div>
                            <div class=\"text-right\">
                                <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_register\" disabled>
                                    <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                    <span class=\"btn-inner--text\">Registrarme</span>
                                    <i class=\"fas fa-check\"></i>
                                </button>
                            </div>
                            <input type=\"hidden\" name=\"token\" id=\"token\" value=\"\">
                        </form>
                    </div>
                    <div class=\"card-footer text-center\">
                        <span class=\"text-muted\">¿Tienes cuenta?</span> <a href=\"";
        // line 70
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "autenticacion\">Iniciar sesión</a>
                    </div>
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
        echo "    <script src=\"https://www.google.com/recaptcha/api.js?render=6Lfl2qcUAAAAADf5kJ33rnZFOUEM3jh3NhiCW4Hj\"></script>
    <script src=\"assets/jscontrollers/autenticacion/registro.js\"></script>
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
        return array (  121 => 82,  118 => 81,  104 => 70,  86 => 55,  32 => 3,  29 => 2,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends 'overall/layout' %}
{% block appBody %}

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
                                    <input type=\"email\" name=\"r_name\" class=\"form-control form-control-sm\" placeholder=\"Nombre completo\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"email\" name=\"r_mail\" class=\"form-control form-control-sm\" placeholder=\"Correo electrónico\">
                                    <div class=\"input-group-append\">
                                        <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_password\" class=\"form-control form-control-sm\" placeholder=\"Contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"form-group\">
                                <div class=\"input-group input-group-transparent mb-2\">
                                    <input type=\"password\" name=\"r_repassword\" class=\"form-control form-control-sm\" placeholder=\"Confirmar contraseña\">
                                    <div class=\"input-group-append\" style=\"cursor:pointer;\">
                                        <span class=\"input-group-text\">
                                            <i class=\"fas fa-lock\"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class=\"mt-4\">
                                <div class=\"custom-control custom-checkbox mb-3\">
                                    <input type=\"checkbox\" class=\"custom-control-input\" name=\"politicas\" id=\"politicas\" value=\"acepto\">
                                    <label class=\"custom-control-label\" for=\"politicas\" style=\"line-height:24px;\">
                                        He leído y acepto la <a href=\"{{config.build.url}}informacion/privacidad\">política de privacidad</a>
                                    </label>
                                </div>
                            </div>
                            <div class=\"text-right\">
                                <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_register\" disabled>
                                    <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                    <span class=\"btn-inner--text\">Registrarme</span>
                                    <i class=\"fas fa-check\"></i>
                                </button>
                            </div>
                            <input type=\"hidden\" name=\"token\" id=\"token\" value=\"\">
                        </form>
                    </div>
                    <div class=\"card-footer text-center\">
                        <span class=\"text-muted\">¿Tienes cuenta?</span> <a href=\"{{config.build.url}}autenticacion\">Iniciar sesión</a>
                    </div>
                </div>
            </div>

        </div>

    </main>
    
{% endblock %}

{% block appFooter %}
    <script src=\"https://www.google.com/recaptcha/api.js?render=6Lfl2qcUAAAAADf5kJ33rnZFOUEM3jh3NhiCW4Hj\"></script>
    <script src=\"assets/jscontrollers/autenticacion/registro.js\"></script>
{% endblock %}", "autenticacion/registro.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\autenticacion\\registro.twig");
    }
}
