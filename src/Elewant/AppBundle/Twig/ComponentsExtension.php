<?php

declare(strict_types=1);

namespace Elewant\AppBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ComponentsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('alert', [$this, 'alert'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('icon', [$this, 'icon'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function alert(Environment $env, string $message, string $type = 'info', string $icon = ''): string
    {
        if (!$icon) {
            if ($type === 'success') {
                $icon = 'check-circle-o';
            } elseif ($type === 'warning') {
                $icon = 'exclamation-triangle';
            } elseif ($type === 'danger') {
                $icon = 'exclamation-circle';
            } else {
                $icon = 'info-circle';
            }
        }

        $template = $env->createTemplate(
            <<<EOT
<div class="alert alert-{{ type }} alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    {{ icon(icon) }} {{ message }}
</div>
EOT
        );

        return $template->render(
            ['message' => $message, 'type' => $type, 'icon' => $icon]
        );
    }

    public function icon(Environment $env, string $icons, array $attributes = []): string
    {
        $icons = preg_split('/\s+/', $icons);
        $icons = array_map(
            function (string $part) use ($env) {
                return 'fa-' . $part . ' ';
            },
            $icons
        );
        $icons = implode('', $icons);

        $attributes['class']       = 'fa ' . trim($icons . ($attributes['class'] ?? ''));
        $attributes['aria-hidden'] = 'true';

        $html = '<i';
        foreach ($attributes as $attribute => $value) {
            $html .= sprintf(
                ' %s="%s"',
                twig_escape_filter($env, $attribute, 'html'),
                twig_escape_filter($env, $value, 'html')
            );
        }
        $html .= '></i>';

        return $html;
    }
}
