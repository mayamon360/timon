<?php

/* overall/layout.twig */
class __TwigTemplate_06e7b447bc816e69cd1f21bdf78845c002d961368bf5fac732208408e0824708 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'appHeader' => array($this, 'block_appHeader'),
            'appTitle' => array($this, 'block_appTitle'),
            'appBody' => array($this, 'block_appBody'),
            'appFooter' => array($this, 'block_appFooter'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"es\">
<head>
";
        // line 5
        echo "    ";
        echo $this->env->getExtension('Ocrend\Kernel\Helpers\Functions')->base_assets();
        echo "
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
    <meta name=\"theme-color\" id=\"theme-color\" content=\"";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor1", array()), "html", null, true);
        echo "\"/>
    <link rel=\"icon\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "assets/plantilla/img/logotipo/logotipo.png\">
    <meta name=\"title\" content=\"El Timón Librería\">
    <meta name=\"description\" content=\"Teléfono 01 722 167 0727. Nos preocupamos por conseguir el título que estás buscando.\">
    <meta name=\"keyword\" content=\"\"> 
";
        // line 14
        echo "    <link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap\" rel=\"stylesheet\"> 
    <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Asap+Condensed:400,500,600,700&display=swap\"> 
";
        // line 17
        echo "    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css\">
";
        // line 19
        echo "    <link rel=\"stylesheet\" href=\"https://webpixels.github.io/boomerang-ui-kit/assets/css/theme.css\">
";
        // line 21
        echo "    <link rel=\"stylesheet\" href=\"https://webpixels.github.io/boomerang-ui-kit/assets/vendor/jquery-scrollbar/css/jquery-scrollbar.css\">
";
        // line 23
        echo "    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css\">
";
        // line 25
        echo "    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css\">
";
        // line 27
        echo "    <link rel=\"stylesheet\" href=\"assets/plantilla/plantilla.css\">
    <style>
    :root {
        --appColor1-HEX:";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor1", array()), "html", null, true);
        echo "!important;--appColor2-HEX:";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor2", array()), "html", null, true);
        echo "!important;--appColor3-HEX:";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor3", array()), "html", null, true);
        echo "!important;--appColor4-HEX:";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor4", array()), "html", null, true);
        echo "!important;
    } 
    </style>
";
        // line 33
        $this->displayBlock('appHeader', $context, $blocks);
        // line 35
        $this->displayBlock('appTitle', $context, $blocks);
        // line 38
        echo "</head>
<body>
";
        // line 40
        $this->loadTemplate("overall/header", "overall/layout.twig", 40)->display($context);
        // line 41
        echo "    ";
        $this->displayBlock('appBody', $context, $blocks);
        // line 43
        echo "    ";
        $this->loadTemplate("overall/footer", "overall/layout.twig", 43)->display($context);
        // line 44
        echo "    ";
        // line 45
        echo "    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>
    ";
        // line 47
        echo "    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
    ";
        // line 49
        echo "    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>
    ";
        // line 51
        echo "    <script defer src=\"https://use.fontawesome.com/releases/v5.7.2/js/all.js\" integrity=\"sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP\" crossorigin=\"anonymous\"></script>
    ";
        // line 53
        echo "    <script src=\"https://webpixels.github.io/boomerang-ui-kit/assets/js/theme.js\"></script>
    ";
        // line 55
        echo "    <script src=\"https://webpixels.github.io/boomerang-ui-kit/assets/vendor/jquery-scrollbar/js/jquery-scrollbar.min.js\"></script>
    ";
        // line 57
        echo "    <script src=\"https://webpixels.github.io/boomerang-ui-kit/assets/vendor/jquery-scrollLock/jquery-scrollLock.min.js\"></script>
    ";
        // line 59
        echo "    <script src=\"https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js\"></script>
    ";
        // line 61
        echo "    <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js\"></script>
    ";
        // line 63
        echo "    <script src=\"assets/plugins/jquery-touchSwipe/jquery.touchSwipe.min.js\"></script>
    ";
        // line 65
        echo "    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js\"></script>
    ";
        // line 67
        echo "    <script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>
    ";
        // line 69
        echo "    <script src=\"assets/plantilla/plantilla.js\"></script>
    ";
        // line 70
        $this->displayBlock('appFooter', $context, $blocks);
        // line 72
        echo "</body>
</html>";
    }

    // line 33
    public function block_appHeader($context, array $blocks = array())
    {
    }

    // line 35
    public function block_appTitle($context, array $blocks = array())
    {
        // line 36
        echo "    <title>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "</title>
";
    }

    // line 41
    public function block_appBody($context, array $blocks = array())
    {
        // line 42
        echo "    ";
    }

    // line 70
    public function block_appFooter($context, array $blocks = array())
    {
        // line 71
        echo "    ";
    }

    public function getTemplateName()
    {
        return "overall/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  171 => 71,  168 => 70,  164 => 42,  161 => 41,  154 => 36,  151 => 35,  146 => 33,  141 => 72,  139 => 70,  136 => 69,  133 => 67,  130 => 65,  127 => 63,  124 => 61,  121 => 59,  118 => 57,  115 => 55,  112 => 53,  109 => 51,  106 => 49,  103 => 47,  100 => 45,  98 => 44,  95 => 43,  92 => 41,  90 => 40,  86 => 38,  84 => 35,  82 => 33,  70 => 30,  65 => 27,  62 => 25,  59 => 23,  56 => 21,  53 => 19,  50 => 17,  46 => 14,  39 => 9,  35 => 8,  28 => 5,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/layout.twig", "C:\\xampp\\htdocs\\website\\app\\templates\\overall\\layout.twig");
    }
}
