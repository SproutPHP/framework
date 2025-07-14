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

/* home.twig */
class __TwigTemplate_83448a0ca4a386895918eb11542666e9 extends Template
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

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layouts/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("layouts/base.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Home — SproutPHP";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "  <h1>";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["title"] ?? null), "html", null, true);
        yield "</h1>
  <p>Welcome to <strong>SproutPHP</strong>, the seed-to-plant minimal PHP framework 🌱</p>
  <p>HTMX-ready, JS-optional, and developer-happy.</p>

  <button 
    hx-get=\"/home\" 
    hx-target=\"#result\" 
    hx-swap=\"innerHTML\"
    class=\"contrast\"
  >
    Click to Sprout
  </button>

  <div id=\"result\" style=\"margin-top: 1rem;\"></div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "home.twig";
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
        return array (  70 => 6,  63 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends \"layouts/base.twig\" %}

{% block title %}Home — SproutPHP{% endblock %}

{% block content %}
  <h1>{{ title }}</h1>
  <p>Welcome to <strong>SproutPHP</strong>, the seed-to-plant minimal PHP framework 🌱</p>
  <p>HTMX-ready, JS-optional, and developer-happy.</p>

  <button 
    hx-get=\"/home\" 
    hx-target=\"#result\" 
    hx-swap=\"innerHTML\"
    class=\"contrast\"
  >
    Click to Sprout
  </button>

  <div id=\"result\" style=\"margin-top: 1rem;\"></div>
{% endblock %}
", "home.twig", "D:\\01293\\SproutPHP\\app\\Views\\home.twig");
    }
}
