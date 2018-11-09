<?php

declare(strict_types=1);

namespace Elewant\Webapp\Infrastructure\Twig;

use Throwable;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig_Error_Loader as LoaderError;
use Twig_Error_Syntax as SyntaxError;

final class ComponentsExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('alert', [$this, 'alert'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('elephpant', [$this, 'elephpant'], ['needs_environment' => true, 'is_safe' => ['html']]),
            new TwigFunction('icon', [$this, 'icon'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @param Environment $env
     * @param string      $message
     * @param string      $type
     * @param string      $icon
     *
     * @return string
     * @throws Throwable
     * @throws LoaderError
     * @throws SyntaxError
     */
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

    /**
     * @param Environment $env
     * @param string      $breed
     * @param int|null    $amount
     * @param bool        $allowControl
     *
     * @return string
     * @throws LoaderError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function elephpant(Environment $env, string $breed, ?int $amount = null, bool $allowControl = false): string
    {
        $template = $env->createTemplate(
            <<<EOT
<div class="elephpant" data-breed="{{ breed }}">
    <div class="elephpant-icon {{ breed|breed_size }} {{ breed|breed_name }} {{ breed|breed_color }}">
        <svg class=data-name="elephpant" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1103.78 771.41">
            <path d="M133.13,218c0-.09,0-.19,0-.28a26.67,26.67,0,0,1,.76-5.17C138,194.78,170.88,99,248.66,89.45c80.68-9.88,36-4.31,64.89-8.36a19,19,0,0,0,10.82-5.43c30.88-31.09,38.21-56.07,78.18-56.07,41.92,0,55.92,27.95,68.15,27.95s15.74-27.95,50.67-27.95c30.09,0,66.68,23.33,80.7,41a18.86,18.86,0,0,0,16.19,7.12c66.95-5.1,263.2-21.45,348.51,39.25l31,22c10,7.14,19.43,15.43,29.07,23a104.64,104.64,0,0,0,9.22,6,27.39,27.39,0,0,0,17.21,4c28-2.8,39.59-5.7,51.66-9.59a18.85,18.85,0,0,0,8.37-5.47c12.49-14.17,38.77-41.61,57.83-41.61,37,0,50.65,26.91,50.65,37.39s14,13.74,14,26-12.77,13-12.77,29.42c0,11.57-18.67,31.24-37.88,31.24-17.48,0-42.19-1.45-58.34-24.07a18.63,18.63,0,0,0-15-7.82,114.91,114.91,0,0,0-19,1.43,18.85,18.85,0,0,0-11.95,30.19c14.61,18.76,21.8,29.26,20.48,92.83-1.58,75.82-6,126-21.06,142.88a18.67,18.67,0,0,0-4.66,13.21c2.13,51.78-1,180.38-2,233.1a48.3,48.3,0,0,1-15.27,34.48c-28.18,26.27-50.23,33.93-105.51,33.93-59.72,0-95.57-10.44-115.83-28.56a18.47,18.47,0,0,0-12.34-4.61c-20.81,0-43.78-.89-64.29-17.17a41.6,41.6,0,0,1-15.46-32.26c-.27-28-1.18-44.29-1.53-78.92-.19-19.06-18.34-33.2-36.7-28.06-12.4,3.47-10.6,11.54-10.6,21.91,0,52.4,15.72,115.28-11.35,138S600,789.84,573.79,789.84c-24.3,0-101.17-6-122-43.1A19.08,19.08,0,0,0,435,736.9c-21.44.21-31.75,5.41-47.24-10.1a42.38,42.38,0,0,1-12.18-28.37c-1.82-47.81.58-149.57.82-212.05a18.82,18.82,0,0,0-8.12-15.55c-45.11-31.15-82.78,11.25-84.43,34.32-3.49,24.45-3.49,209.59-22.71,232.3s-45.67,26.2-64.88,26.2c-17.66,0-40.62-7.38-58.53-27.56a18.57,18.57,0,0,1-4.6-12.34Z" transform="translate(-132.55 -19.01)" style="stroke-miterlimit: 10; stroke-width: 0">
        </svg>
    </div>
    <h4 class="elephpant-title">{{ ('breed.' ~ breed)|trans({}, 'herd') }}</h4>
    {% if allow_control %}
    <p class="elephpant-controls">
        {{ icon('minus-square-o fw', {'class': 'elephpant-abandon', 'data-breed': breed}) }}
        <span class="elephpant-amount badge badge-pill">{{ amount|default(0) }}</span>
        {{ icon('plus-square-o fw', {'class': 'elephpant-adopt', 'data-breed': breed}) }}
    </p>
    {% elseif amount is not null %}
    <p class="elephpant-controls">
        <span class="elephpant-amount badge badge-pill">{{ amount }}</span>
    </p>
    {% endif %}
</div>
EOT
        );

        return $template->render(
            ['breed' => $breed, 'amount' => $amount, 'allow_control' => $allowControl]
        );
    }

    /**
     * @param Environment $env
     * @param string      $icons
     * @param array       $attributes
     *
     * @return string
     * @throws LoaderError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function icon(Environment $env, string $icons, array $attributes = []): string
    {
        $icons = preg_split('/\s+/', $icons);
        $icons = array_map(
            function (string $part): string {
                return 'fa-' . $part . ' ';
            },
            $icons
        );
        $icons = implode('', $icons);

        $attributes['class']       = 'fa ' . trim($icons . ($attributes['class'] ?? ''));
        $attributes['aria-hidden'] = 'true';

        $template = $env->createTemplate(
            <<<EOT
<i{% for key, value in attributes %} {{ key }}="{{ value }}"{% endfor %}></i>
EOT
        );

        return $template->render(
            ['attributes' => $attributes]
        );
    }
}
