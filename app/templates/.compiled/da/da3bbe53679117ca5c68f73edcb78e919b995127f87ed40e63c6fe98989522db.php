<?php

/* informacion/informacion.twig */
class __TwigTemplate_3209f7c078e67a44cec537dd68e580728e415279138287b773f07a124fb67ef4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("overall/layout", "informacion/informacion.twig", 1);
        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
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

    // line 3
    public function block_appTitle($context, array $blocks = array())
    {
        // line 4
        echo "    <title>Contacto</title>
    <meta name=\"title\" content=\"Contacto\">
";
    }

    // line 8
    public function block_appHeader($context, array $blocks = array())
    {
        // line 9
        echo "<script src=\"https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit\"
        async defer>
</script>
";
    }

    // line 14
    public function block_appBody($context, array $blocks = array())
    {
        // line 15
        echo "
    <main class=\"container mt-5\">
        <div class=\"row\">
            <div class=\"col-12 mb-4\">
                <iframe class=\"z-depth-3 animated zoomInDown\" src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2946.147151745019!2d-99.65431244250686!3d19.286542394722712!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x70c78187c6b8d5eb!2zRWwgdGltw7NuIExpYnJlcsOtYQ!5e0!3m2!1ses-419!2smx!4v1593722685645!5m2!1ses-419!2smx\" width=\"100%\" height=\"300px\" frameborder=\"0\" style=\"border:0;\" allowfullscreen=\"\" aria-hidden=\"false\" tabindex=\"0\"></iframe>
            </div>
        </div>
            
            <div class=\"row cols-xs-space cols-sm-space cols-md-space\">
                
                <div class=\"col-md-6 destacados animated fadeInUp\">
                    
                    <p class=\"m-0 p-0 text-muted font-weight-bold\"><small class=\"font-weight-bold\">Datos de</small></p>
                    <h1 class=\"m-0 p-0 titulo_destacado\"><i class=\"fas fa-address-book\"></i> Contacto</h1>
\t\t\t        <div class=\"my-1 mb-4 separador separador_color\"></div>
\t\t\t        
\t\t\t        <h5 class=\"text-dark font-weight-normal\">El timón librería</h5>
\t\t\t        <ul class=\"fa-ul text-muted\">
\t\t\t            <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fas fa-map-marker-alt text-app\"></i></span>Instituto Literario, esquina, Juan Aldama 300-A, 50130 Toluca de Lerdo, Méx.</li>
                        <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fas fa-phone text-app\"></i></span>722 167 0727</li>
                        <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fab fa-whatsapp text-app\"></i></span><a target=\"_blank\" href=\"https://api.whatsapp.com/send?phone=+527222549526\">722 254 9526</a></li>
                        <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fas fa-envelope text-app\"></i></span><a href=\"mailto:eltimonlibreria@gmail.com\">eltimonlibreria@gmail.com</a></li>
                        <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 fab fa-facebook-square text-app\"></i></span><a target=\"_blank\" href=\"https://www.facebook.com/El-tim%C3%B3n-librer%C3%ADa-247638102062702/\">El timón librería</a></li>
                        <li class=\"py-1\"><span class=\"fa-li\"><i class=\"mr-2 far fa-clock text-app\"></i></span>Lunes a Sábado 10:00 - 19:00</li>
                    </ul>
                </div>
                
                <div class=\"col-md-6 destacados animated fadeInRight\">
                    <p class=\"m-0 p-0 text-muted font-weight-bold animated fadeIn\"><small class=\"font-weight-bold\">Envianos un</small></p>
                        <h1 class=\"m-0 p-0 animated fadeIn titulo_destacado\"><i class=\"fas fa-envelope-open-text\"></i> Mensaje</h1>
\t\t\t        <div class=\"my-1 animated fadeIn separador separador_color\"></div>
\t\t\t        <form class=\"mt-4\" id=\"form_send\">
\t\t\t            
\t\t\t            <div class=\"form-group\">
\t\t\t                <label for=\"subject\" class=\"text-muted\">Asunto:</label>
                            <div class=\"input-group input-group-transparent\">
                                <input type=\"text\" class=\"form-control form-control-sm\" name=\"subject\" id=\"subject\" autocomplete=\"off\">
                                <div class=\"input-group-append\">
                                    <span class=\"input-group-text\"><i class=\"fas fa-info-circle\"></i></span>
                                </div>
                            </div>
                        </div>
                        
\t\t\t            <div class=\"form-group\">
\t\t\t                <label for=\"name\" class=\"text-muted\">Nombre completo:</label>
                            <div class=\"input-group input-group-transparent\">
                                <input type=\"text\" class=\"form-control form-control-sm\" name=\"name\" id=\"name\" autocomplete=\"off\">
                                <div class=\"input-group-append\">
                                    <span class=\"input-group-text\"><i class=\"fas fa-user\"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <label for=\"email\" class=\"text-muted\">Correo electrónico:</label>
                            <div class=\"input-group input-group-transparent\">
                                <input type=\"email\" class=\"form-control form-control-sm\" name=\"email\" id=\"email\">
                                <div class=\"input-group-append\">
                                    <span class=\"input-group-text\"><i class=\"fas fa-envelope\"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class=\"form-group\">
                            <label for=\"message\" class=\"text-muted\">Mensaje:</label>
                            <textarea class=\"form-control form-control-sm\" name=\"message\" id=\"message\" rows=\"6\" resize=\"none\" autocomplete=\"off\"></textarea>
                        </div>
                        
                        <div id=\"g-recaptcha\" style=\"margin:auto; max-width:100%;\"></div>
                        
                        <!--<div class=\"custom-control custom-checkbox mt-3 mb-3\">
                            <input type=\"checkbox\" class=\"custom-control-input\" name=\"politics\" id=\"politics\" value=\"acepto\">
                            <label class=\"custom-control-label\" for=\"politics\" style=\"line-height:24px;\">
                                He leído y acepto la <a href=\"";
        // line 86
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "informacion/privacidad\">política de privacidad</a>
                            </label>
                        </div>-->
                        
                        <div class=\"text-right\">
                            <button type=\"button\" class=\"btn btn-sm btn-icon boton_color\" id=\"btn_send\" disabled>
                                <span class=\"spinner-grow spinner-grow-sm d-none\" role=\"status\" aria-hidden=\"true\"></span>
                                <span class=\"btn-inner--text\">Enviar mensaje</span>
                                <i class=\"fas fa-paper-plane\"></i>
                            </button>
                        </div>
\t\t\t        </form>
                </div>
          </div>
            
    </main>
    
";
    }

    // line 105
    public function block_appFooter($context, array $blocks = array())
    {
        // line 106
        echo "\t<script src=\"assets/jscontrollers/informacion/contacto.js\"></script>
";
    }

    public function getTemplateName()
    {
        return "informacion/informacion.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  151 => 106,  148 => 105,  126 => 86,  53 => 15,  50 => 14,  43 => 9,  40 => 8,  34 => 4,  31 => 3,  11 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "informacion/informacion.twig", "/home4/eltimonl/public_html/app/templates/informacion/informacion.twig");
    }
}
