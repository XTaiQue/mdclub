<?php

declare(strict_types=1);

namespace MDClub\Library;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;

/**
 * PhpRenderer 重写，使其具有主题功能
 */
class View extends PhpRenderer
{
    /**
     * 当前使用的主题
     *
     * @var string
     */
    protected $theme;

    /**
     * 默认主题
     *
     * @var string
     */
    protected $defaultTheme = 'default';

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(__DIR__ . '/../../templates/');

        $this->theme = $container->get('option')->theme;
    }

    /**
     * 返回渲染后的模板字符串
     *
     * @param  string $template
     * @param  array  $data
     * @return mixed
     */
    public function fetch($template, array $data = [])
    {
        $theme = is_file($this->templatePath . $this->theme. $template)
            ? $this->theme
            : $this->defaultTheme;

        return parent::fetch('/' . $theme . $template, $data);
    }

    /**
     * 渲染模板
     *
     * @param  ResponseInterface $response
     * @param  string            $template
     * @param  array             $data
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $data = []): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'text/html; charset=utf-8');

        return parent::render($response, $template, $data);
    }
}