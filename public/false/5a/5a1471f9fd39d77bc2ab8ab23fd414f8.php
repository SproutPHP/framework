<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* layouts/base.twig */
class __TwigTemplate_141515ffdf980e7b3b30b46babbabd30 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"en\" hx-boost=\"true\" data-framework=\"SproutPHP\" data-htmx=\"true\" data-theme=\"dark\">
\t<head>
\t\t<meta charset=\"UTF-8\">
\t\t<meta name=\"htmx-powered-by\" content=\"SproutPHP\">
\t\t<title>
\t\t\t";
        // line 7
        yield from $this->unwrap()->yieldBlock('title', $context, $blocks);
        // line 10
        yield "\t\t</title>
\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t\t<link rel=\"stylesheet\" href=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(assets("css/sprout.min.css"), "html", null, true);
        yield "\">
\t\t<script src=\"";
        // line 13
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(assets("js/sprout.min.js"), "html", null, true);
        yield "\"></script>
\t</head>
\t<body>
\t\t<main class=\"container\">
\t\t\t";
        // line 17
        yield from $this->load("components/navbar.twig", 17)->unwrap()->yield($context);
        // line 18
        yield "\t\t\t";
        yield from $this->unwrap()->yieldBlock('content', $context, $blocks);
        // line 19
        yield "\t\t</main>

\t\t";
        // line 21
        if ((config("app.env", "local") == "local")) {
            // line 22
            yield "\t\t\t<div style=\"z-index:9999;position:fixed;bottom:75px;right:10px;background:#eef;border:1px solid #ccd;padding:0.5rem;font-family:monospace;font-size:0.8rem;border-radius:4px;\">
\t\t\t\t⚡ HTMX Active
\t\t\t\t<br/>
\t\t\t\t📃
\t\t\t\t<a href=\"https://htmx.org/docs/\" target=\"_blank\">HTMX Docs ↗</a>
\t\t\t</div>
\t\t";
        }
        // line 29
        yield "
\t\t";
        // line 30
        if ((($tmp = ($context["app_debug"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 31
            yield "\t\t\t";
            yield (is_scalar($tmp = ($context["debugbar"] ?? null)) ? new Markup($tmp, $this->env->getCharset()) : $tmp);
            yield "
\t\t";
        }
        // line 33
        yield "\t</body>
</html>
";
        yield from [];
    }

    // line 7
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 8
        yield "\t\t\t\t";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(config("app.name", "SproutPHP"), "html", null, true);
        yield "
\t\t\t";
        yield from [];
    }

    // line 18
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "layouts/base.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  122 => 18,  114 => 8,  107 => 7,  100 => 33,  94 => 31,  92 => 30,  89 => 29,  80 => 22,  78 => 21,  74 => 19,  71 => 18,  69 => 17,  62 => 13,  58 => 12,  54 => 10,  52 => 7,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\" hx-boost=\"true\" data-framework=\"SproutPHP\" data-htmx=\"true\" data-theme=\"dark\">
\t<head>
\t\t<meta charset=\"UTF-8\">
\t\t<meta name=\"htmx-powered-by\" content=\"SproutPHP\">
\t\t<title>
\t\t\t{% block title %}
\t\t\t\t{{ config('app.name', 'SproutPHP') }}
\t\t\t{% endblock %}
\t\t</title>
\t\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
\t\t<link rel=\"stylesheet\" href=\"{{ assets('css/sprout.min.css') }}\">
\t\t<script src=\"{{ assets('js/sprout.min.js') }}\"></script>
\t</head>
\t<body>
\t\t<main class=\"container\">
\t\t\t{% include 'components/navbar.twig' %}
\t\t\t{% block content %}{% endblock %}
\t\t</main>

\t\t{% if config('app.env', 'local') == 'local' %}
\t\t\t<div style=\"z-index:9999;position:fixed;bottom:75px;right:10px;background:#eef;border:1px solid #ccd;padding:0.5rem;font-family:monospace;font-size:0.8rem;border-radius:4px;\">
\t\t\t\t⚡ HTMX Active
\t\t\t\t<br/>
\t\t\t\t📃
\t\t\t\t<a href=\"https://htmx.org/docs/\" target=\"_blank\">HTMX Docs ↗</a>
\t\t\t</div>
\t\t{% endif %}

\t\t{% if app_debug %}
\t\t\t{{ debugbar|raw }}
\t\t{% endif %}
\t</body>
</html>
", "layouts/base.twig", "D:\\01293\\SproutPHP\\app\\Views\\layouts\\base.twig");
    }
}
