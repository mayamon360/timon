<?php

/* overall/layout.twig */
class __TwigTemplate_ef3e0bad5826b34c45ed603cc48013bf9793726b53c12ad0e0c0bc3e306bfe12 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'appTitle' => array($this, 'block_appTitle'),
            'appDescription' => array($this, 'block_appDescription'),
            'appKeyword' => array($this, 'block_appKeyword'),
            'appImage' => array($this, 'block_appImage'),
            'ogTitle' => array($this, 'block_ogTitle'),
            'ogUrl' => array($this, 'block_ogUrl'),
            'ogdescription' => array($this, 'block_ogdescription'),
            'ogImage' => array($this, 'block_ogImage'),
            'ogType' => array($this, 'block_ogType'),
            'ogSiteName' => array($this, 'block_ogSiteName'),
            'ogLocale' => array($this, 'block_ogLocale'),
            'appHeader' => array($this, 'block_appHeader'),
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
    <meta http-equiv=\"Content-Security-Policy\" content=\"upgrade-insecure-requests\">
";
        // line 5
        $this->displayBlock('appTitle', $context, $blocks);
        // line 9
        $this->displayBlock('appDescription', $context, $blocks);
        // line 12
        $this->displayBlock('appKeyword', $context, $blocks);
        // line 15
        $this->displayBlock('appImage', $context, $blocks);
        // line 18
        echo "     <meta name=\"revisit\" content=\"15 days\">
     <meta name=\"revisit-after\" content=\"1 days\">
     <meta name=\"robots\" content=\"index,follow\">
";
        // line 21
        $this->displayBlock('ogTitle', $context, $blocks);
        // line 24
        $this->displayBlock('ogUrl', $context, $blocks);
        // line 27
        $this->displayBlock('ogdescription', $context, $blocks);
        // line 30
        $this->displayBlock('ogImage', $context, $blocks);
        // line 33
        $this->displayBlock('ogType', $context, $blocks);
        // line 36
        $this->displayBlock('ogSiteName', $context, $blocks);
        // line 39
        $this->displayBlock('ogLocale', $context, $blocks);
        // line 42
        echo "
     ";
        // line 43
        echo $this->env->getExtension('Ocrend\Kernel\Helpers\Functions')->base_assets();
        echo "
     <meta charset=\"UTF-8\">
     <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no\">
     <meta name=\"theme-color\" id=\"theme-color\" content=\"";
        // line 46
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor1", array()), "html", null, true);
        echo "\"/>
     <link rel=\"icon\" href=\"";
        // line 47
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "favicon", array()), "html", null, true);
        echo "\" sizes=\"any\" type=\"image/svg+xml\">
";
        // line 49
        echo "     <link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap\" rel=\"stylesheet\"> 
     <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Asap+Condensed:400,500,600,700&display=swap\">
";
        // line 52
        echo "     <link rel=\"stylesheet\" href=\"./assets/plugins/animate/animate.css\">
";
        // line 54
        echo "     <link rel=\"stylesheet\" href=\"./assets/plantilla/boomerang-ui-kit/assets/css/theme.css\">
";
        // line 56
        echo "     <link rel=\"stylesheet\" href=\"./assets/plugins/carousel/owl.carousel.min.css\">
";
        // line 58
        echo "     <link rel=\"stylesheet\" href=\"./assets/plantilla/plantilla.css\">
     <style>
     :root {
         --appColor1-HEX:";
        // line 61
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
     <!-- Global site tag (gtag.js) - Google Analytics -->
     <script async src=\"https://www.googletagmanager.com/gtag/js?id=UA-85055210-3\"></script>
     <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());

         gtag('config', 'UA-85055210-3');
    </script>
    <script type=\"application/ld+json\">
    {
    \t\"@context\" : \"https://schema.org\",
    \t\"@type\" : \"Store\",
    \t\"name\" : \"";
        // line 77
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\",
    \t\"url\" : \"";
        // line 78
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "\",
    \t\"logo\": \"";
        // line 79
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "logo", array()), "html", null, true);
        echo "\",
    \t\"address\": {
            \"@type\": \"PostalAddress\",
            \"streetAddress\": \"Instituto Literario, esquina, Juan Aldama 300-A\",
            \"addressLocality\": \"Toluca de Lerdo\",
            \"addressRegion\": \"México\",
            \"addressCountry\" : \"México\",
            \"postalCode\": \"50130\" 
        },
        \"priceRange\": \"\$\$\",
        \"image\": \"";
        // line 89
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "logo", array()), "html", null, true);
        echo "\",
        \"telephone\": \"722 167 0727 \",
        \"email\": \"montiel_989@hotmail.com\"
    }
    </script>
    <script type=\"application/ld+json\">
    {
        \"@context\": \"https://schema.org\",
        \"@type\": \"WebSite\",
        \"url\": \"";
        // line 98
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\"
    }
    </script>
    <script type=\"application/ld+json\">
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"Organization\",
      \"url\": \"";
        // line 105
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "\",
      \"logo\": \"";
        // line 106
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "logo", array()), "html", null, true);
        echo "\"
    }
    </script>
    
";
        // line 110
        $this->displayBlock('appHeader', $context, $blocks);
        // line 112
        echo "</head>
<body style=\"overflow-x:hidden;\">
    <!--<div class=\"tpl-snow\">
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
</div>-->
";
        // line 164
        $this->loadTemplate("overall/header", "overall/layout.twig", 164)->display($context);
        // line 165
        echo "    ";
        $this->displayBlock('appBody', $context, $blocks);
        // line 167
        echo "    ";
        $this->loadTemplate("overall/footer", "overall/layout.twig", 167)->display($context);
        // line 168
        echo "    ";
        // line 169
        echo "    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>
    <!--<script src=\"./assets/vendor/jquery-3.3.1.min.js\"></script>-->
    ";
        // line 172
        echo "    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
    <!--<script src=\"./assets/vendor/popper.min.js\"></script>-->
    ";
        // line 175
        echo "    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>
    <!--<script src=\"./assets/vendor/bootstrap.min.js\"></script>-->
    ";
        // line 178
        echo "    <script defer src=\"https://use.fontawesome.com/releases/v5.7.2/js/all.js\" integrity=\"sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP\" crossorigin=\"anonymous\"></script>
    ";
        // line 180
        echo "    <script src=\"./assets/plugins/jquery-touchSwipe/jquery.touchSwipe.min.js\"></script>
    ";
        // line 182
        echo "    <script src=\"./assets/plugins/carousel/owl.carousel.min.js\"></script>
    ";
        // line 184
        echo "    <script src=\"./assets/plugins/sweetalert/sweetalert.min.js\"></script>
    
    <script src=\"./assets/plantilla/boomerang-ui-kit/assets/js/theme.js\"></script>
    
    ";
        // line 189
        echo "    <script src=\"./assets/plantilla/plantilla.js\"></script>
    ";
        // line 190
        $this->displayBlock('appFooter', $context, $blocks);
        // line 192
        echo "</body>
</html>";
    }

    // line 5
    public function block_appTitle($context, array $blocks = array())
    {
        // line 6
        echo "     <title>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "</title>
     <meta name=\"title\" content=\"";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "\">
";
    }

    // line 9
    public function block_appDescription($context, array $blocks = array())
    {
        // line 10
        echo "     <meta name=\"description\" content=\"";
        echo twig_escape_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "descripcion", array()), 0, 297), "html", null, true);
        echo "\">
";
    }

    // line 12
    public function block_appKeyword($context, array $blocks = array())
    {
        // line 13
        echo "     <meta name=\"keyword\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "palabrasClave", array()), "html", null, true);
        echo "\"> 
";
    }

    // line 15
    public function block_appImage($context, array $blocks = array())
    {
        // line 16
        echo "     <link rel=\"image_src\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
        echo "\">
";
    }

    // line 21
    public function block_ogTitle($context, array $blocks = array())
    {
        echo "  
     <meta property=\"og:title\" content=\"";
        // line 22
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "\">
";
    }

    // line 24
    public function block_ogUrl($context, array $blocks = array())
    {
        // line 25
        echo "     <meta property=\"og:url\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "ruta", array()), "html", null, true);
        echo "\">
";
    }

    // line 27
    public function block_ogdescription($context, array $blocks = array())
    {
        // line 28
        echo "     <meta property=\"og:description\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "descripcion", array()), "html", null, true);
        echo "\">
";
    }

    // line 30
    public function block_ogImage($context, array $blocks = array())
    {
        // line 31
        echo "     <meta property=\"og:image\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
        echo "\">
";
    }

    // line 33
    public function block_ogType($context, array $blocks = array())
    {
        // line 34
        echo "     <meta property=\"og:type\" content=\"website\">
";
    }

    // line 36
    public function block_ogSiteName($context, array $blocks = array())
    {
        // line 37
        echo "     <meta property=\"og:site_name\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\">
";
    }

    // line 39
    public function block_ogLocale($context, array $blocks = array())
    {
        // line 40
        echo "     <meta property=\"og:locale\" content=\"es_MX\">
";
    }

    // line 110
    public function block_appHeader($context, array $blocks = array())
    {
    }

    // line 165
    public function block_appBody($context, array $blocks = array())
    {
        // line 166
        echo "    ";
    }

    // line 190
    public function block_appFooter($context, array $blocks = array())
    {
        // line 191
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
        return array (  415 => 191,  412 => 190,  408 => 166,  405 => 165,  400 => 110,  395 => 40,  392 => 39,  385 => 37,  382 => 36,  377 => 34,  374 => 33,  366 => 31,  363 => 30,  356 => 28,  353 => 27,  345 => 25,  342 => 24,  336 => 22,  331 => 21,  324 => 16,  321 => 15,  314 => 13,  311 => 12,  304 => 10,  301 => 9,  295 => 7,  290 => 6,  287 => 5,  282 => 192,  280 => 190,  277 => 189,  271 => 184,  268 => 182,  265 => 180,  262 => 178,  258 => 175,  254 => 172,  250 => 169,  248 => 168,  245 => 167,  242 => 165,  240 => 164,  186 => 112,  184 => 110,  176 => 106,  172 => 105,  162 => 98,  149 => 89,  135 => 79,  131 => 78,  127 => 77,  102 => 61,  97 => 58,  94 => 56,  91 => 54,  88 => 52,  84 => 49,  79 => 47,  75 => 46,  69 => 43,  66 => 42,  64 => 39,  62 => 36,  60 => 33,  58 => 30,  56 => 27,  54 => 24,  52 => 21,  47 => 18,  45 => 15,  43 => 12,  41 => 9,  39 => 5,  33 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/layout.twig", "/home4/eltimonl/public_html/app/templates/overall/layout.twig");
    }
}
