<?php

/* overall/layout.twig */
class __TwigTemplate_833089f05cf663b129873336c5deb18de6f26c5ee2e064f2db8a1faa10dfccc6 extends Twig_Template
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
";
        // line 4
        $this->displayBlock('appTitle', $context, $blocks);
        // line 8
        $this->displayBlock('appDescription', $context, $blocks);
        // line 11
        $this->displayBlock('appKeyword', $context, $blocks);
        // line 14
        $this->displayBlock('appImage', $context, $blocks);
        // line 17
        echo "     <meta name=\"revisit\" content=\"15 days\">
     <meta name=\"revisit-after\" content=\"1 days\">
     <meta name=\"robots\" content=\"index,follow\">
";
        // line 20
        $this->displayBlock('ogTitle', $context, $blocks);
        // line 23
        $this->displayBlock('ogUrl', $context, $blocks);
        // line 26
        $this->displayBlock('ogdescription', $context, $blocks);
        // line 29
        $this->displayBlock('ogImage', $context, $blocks);
        // line 32
        $this->displayBlock('ogType', $context, $blocks);
        // line 35
        $this->displayBlock('ogSiteName', $context, $blocks);
        // line 38
        $this->displayBlock('ogLocale', $context, $blocks);
        // line 41
        echo "
     ";
        // line 42
        echo $this->env->getExtension('Ocrend\Kernel\Helpers\Functions')->base_assets();
        echo "
     <meta charset=\"UTF-8\">
     <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
     <meta name=\"theme-color\" id=\"theme-color\" content=\"";
        // line 45
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["colores"] ?? null), "appColor1", array()), "html", null, true);
        echo "\"/>
     <link rel=\"icon\" href=\"";
        // line 46
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "favicon", array()), "html", null, true);
        echo "\" sizes=\"any\" type=\"image/svg+xml\">
";
        // line 48
        echo "     <link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap\" rel=\"stylesheet\"> 
     <link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css?family=Asap+Condensed:400,500,600,700&display=swap\"> 
";
        // line 51
        echo "     <link rel=\"stylesheet\" href=\"./assets/plugins/animate/animate.css\">
";
        // line 53
        echo "     <link rel=\"stylesheet\" href=\"./assets/plantilla/boomerang-ui-kit/assets/css/theme.css\">
";
        // line 55
        echo "     <link rel=\"stylesheet\" href=\"./assets/plugins/carousel/owl.carousel.min.css\">
";
        // line 57
        echo "     <link rel=\"stylesheet\" href=\"./assets/plantilla/plantilla.css\">
     <style>
     :root {
         --appColor1-HEX:";
        // line 60
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
    \t\"@context\" : \"http://schema.org\",
    \t\"@type\" : \"Store\",
    \t\"name\" : \"";
        // line 76
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\",
    \t\"url\" : \"";
        // line 77
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "\",
    \t\"logo\": \"";
        // line 78
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
        // line 88
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "logo", array()), "html", null, true);
        echo "\",
        \"telephone\": \"722 167 0727 \",
        \"email\": \"montiel_989@hotmail.com\"
    }
    </script>
    <script type=\"application/ld+json\">
    {
        \"@context\": \"http://schema.org\",
        \"@type\": \"WebSite\",
        \"url\": \"";
        // line 97
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\"
    }
    </script>
    <script type=\"application/ld+json\">
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"Organization\",
      \"url\": \"";
        // line 104
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo "\",
      \"logo\": \"";
        // line 105
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "mailer", array()), "logo", array()), "html", null, true);
        echo "\"
    }
    </script>
    
    <script src=\"https://www.mercadopago.com/v2/security.js\" view=\"\"></script>
";
        // line 110
        $this->displayBlock('appHeader', $context, $blocks);
        // line 112
        echo "</head>
<body style=\"overflow-x:hidden;\">
";
        // line 114
        $this->loadTemplate("overall/header", "overall/layout.twig", 114)->display($context);
        // line 115
        echo "    ";
        $this->displayBlock('appBody', $context, $blocks);
        // line 117
        echo "    ";
        $this->loadTemplate("overall/footer", "overall/layout.twig", 117)->display($context);
        // line 118
        echo "    ";
        // line 119
        echo "    <script src=\"https://code.jquery.com/jquery-3.3.1.min.js\" integrity=\"sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=\" crossorigin=\"anonymous\"></script>
    ";
        // line 121
        echo "    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\" integrity=\"sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\" crossorigin=\"anonymous\"></script>
    ";
        // line 123
        echo "    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\" integrity=\"sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\" crossorigin=\"anonymous\"></script>
    ";
        // line 125
        echo "    <script defer src=\"https://use.fontawesome.com/releases/v5.7.2/js/all.js\" integrity=\"sha384-0pzryjIRos8mFBWMzSSZApWtPl/5++eIfzYmTgBBmXYdhvxPc+XcFEk+zJwDgWbP\" crossorigin=\"anonymous\"></script>
    ";
        // line 127
        echo "    <script src=\"./assets/plugins/jquery-touchSwipe/jquery.touchSwipe.min.js\"></script>
    ";
        // line 129
        echo "    <script src=\"./assets/plugins/carousel/owl.carousel.min.js\"></script>
    ";
        // line 131
        echo "    <script src=\"./assets/plugins/sweetalert/sweetalert.min.js\"></script>
    
    <script src=\"./assets/plantilla/boomerang-ui-kit/assets/js/theme.js\"></script>
    
    ";
        // line 136
        echo "    <script src=\"./assets/plantilla/plantilla.js\"></script>
    ";
        // line 137
        $this->displayBlock('appFooter', $context, $blocks);
        // line 139
        echo "</body>
</html>";
    }

    // line 4
    public function block_appTitle($context, array $blocks = array())
    {
        // line 5
        echo "     <title>";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "</title>
     <meta name=\"title\" content=\"";
        // line 6
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "\">
";
    }

    // line 8
    public function block_appDescription($context, array $blocks = array())
    {
        // line 9
        echo "     <meta name=\"description\" content=\"";
        echo twig_escape_filter($this->env, twig_slice($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "descripcion", array()), 0, 297), "html", null, true);
        echo "\">
";
    }

    // line 11
    public function block_appKeyword($context, array $blocks = array())
    {
        // line 12
        echo "     <meta name=\"keyword\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "palabrasClave", array()), "html", null, true);
        echo "\"> 
";
    }

    // line 14
    public function block_appImage($context, array $blocks = array())
    {
        // line 15
        echo "     <link rel=\"image_src\" href=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
        echo "\">
";
    }

    // line 20
    public function block_ogTitle($context, array $blocks = array())
    {
        echo "  
     <meta property=\"og:title\" content=\"";
        // line 21
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "titulo", array()), "html", null, true);
        echo "\">
";
    }

    // line 23
    public function block_ogUrl($context, array $blocks = array())
    {
        // line 24
        echo "     <meta property=\"og:url\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "ruta", array()), "html", null, true);
        echo "\">
";
    }

    // line 26
    public function block_ogdescription($context, array $blocks = array())
    {
        // line 27
        echo "     <meta property=\"og:description\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "descripcion", array()), "html", null, true);
        echo "\">
";
    }

    // line 29
    public function block_ogImage($context, array $blocks = array())
    {
        // line 30
        echo "     <meta property=\"og:image\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "url", array()), "html", null, true);
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), ($context["cabeceras"] ?? null), "portada", array()), "html", null, true);
        echo "\">
";
    }

    // line 32
    public function block_ogType($context, array $blocks = array())
    {
        // line 33
        echo "     <meta property=\"og:type\" content=\"website\">
";
    }

    // line 35
    public function block_ogSiteName($context, array $blocks = array())
    {
        // line 36
        echo "     <meta property=\"og:site_name\" content=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->getSourceContext(), twig_get_attribute($this->env, $this->getSourceContext(), ($context["config"] ?? null), "build", array()), "name", array()), "html", null, true);
        echo "\">
";
    }

    // line 38
    public function block_ogLocale($context, array $blocks = array())
    {
        // line 39
        echo "     <meta property=\"og:locale\" content=\"es_MX\">
";
    }

    // line 110
    public function block_appHeader($context, array $blocks = array())
    {
    }

    // line 115
    public function block_appBody($context, array $blocks = array())
    {
        // line 116
        echo "    ";
    }

    // line 137
    public function block_appFooter($context, array $blocks = array())
    {
        // line 138
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
        return array (  362 => 138,  359 => 137,  355 => 116,  352 => 115,  347 => 110,  342 => 39,  339 => 38,  332 => 36,  329 => 35,  324 => 33,  321 => 32,  313 => 30,  310 => 29,  303 => 27,  300 => 26,  292 => 24,  289 => 23,  283 => 21,  278 => 20,  271 => 15,  268 => 14,  261 => 12,  258 => 11,  251 => 9,  248 => 8,  242 => 6,  237 => 5,  234 => 4,  229 => 139,  227 => 137,  224 => 136,  218 => 131,  215 => 129,  212 => 127,  209 => 125,  206 => 123,  203 => 121,  200 => 119,  198 => 118,  195 => 117,  192 => 115,  190 => 114,  186 => 112,  184 => 110,  175 => 105,  171 => 104,  161 => 97,  148 => 88,  134 => 78,  130 => 77,  126 => 76,  101 => 60,  96 => 57,  93 => 55,  90 => 53,  87 => 51,  83 => 48,  78 => 46,  74 => 45,  68 => 42,  65 => 41,  63 => 38,  61 => 35,  59 => 32,  57 => 29,  55 => 26,  53 => 23,  51 => 20,  46 => 17,  44 => 14,  42 => 11,  40 => 8,  38 => 4,  33 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "overall/layout.twig", "/home4/eltimonl/public_html/app/templates/overall/layout.twig");
    }
}
