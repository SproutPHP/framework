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

/* components/navbar.twig */
class __TwigTemplate_199bfb6223c0e22594dede4c76e5db4a extends Template
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
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 2
        yield "<div class=\"component navbar\">
\t<nav>
\t\t<ul>
\t\t\t<li>
\t\t\t\t<strong class=\"\">
\t\t\t\t\t<img src=\"";
        // line 7
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(assets("img/logo.png"), "html", null, true);
        yield "\" width=\"22px\"/>
\t\t\t\t\tSproutPHP
\t\t\t\t</strong>
\t\t\t</li>
\t\t</ul>
\t\t<ul>
\t\t\t<li><a href=\"#\">About</a></li>
\t\t\t<li><a href=\"#\">Docs</a></li>
\t\t\t<li><a href=\"#\"><img src=\"https://img.shields.io/github/stars/sproutphp/framework?style=social&link=https%3A%2F%2Fgithub.com%2FSproutPHP%2Fframework\"/></a></li>
\t\t</ul>
\t</nav>
</div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "components/navbar.twig";
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
        return array (  49 => 7,  42 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# SproutPHP Component: navbar.twig #}
<div class=\"component navbar\">
\t<nav>
\t\t<ul>
\t\t\t<li>
\t\t\t\t<strong class=\"\">
\t\t\t\t\t<img src=\"{{ assets('img/logo.png') }}\" width=\"22px\"/>
\t\t\t\t\tSproutPHP
\t\t\t\t</strong>
\t\t\t</li>
\t\t</ul>
\t\t<ul>
\t\t\t<li><a href=\"#\">About</a></li>
\t\t\t<li><a href=\"#\">Docs</a></li>
\t\t\t<li><a href=\"#\"><img src=\"https://img.shields.io/github/stars/sproutphp/framework?style=social&link=https%3A%2F%2Fgithub.com%2FSproutPHP%2Fframework\"/></a></li>
\t\t</ul>
\t</nav>
</div>
", "components/navbar.twig", "D:\\01293\\SproutPHP\\app\\Views\\components\\navbar.twig");
    }
}
