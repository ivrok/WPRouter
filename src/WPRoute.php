<?php

namespace Ivrok\WPRouter;

use Ivrok\WPRouter\Exceptions\EmptyRoutePathException;

class WPRoute
{
    public $path;
    private $callback;

    public function __construct(string $path, callable $callback)
    {
        $this->checkPath($path);

        $this->path = $path;
        $this->callback = $callback;
    }

    public function isItThisPath(string $curPath)
    {
        return preg_match('/' . $this->getPathRegex($this->path) . '/', $curPath) > 0;
    }

    public function call($args)
    {
        call_user_func_array($this->callback, $args);
    }

    public function getPathRegex(string $path): string
    {
        return preg_replace('/{[^}]+}/', '([a-zA-Z0-9_\-\.~]+)',
                str_replace('/', '\/', $path)) . '\/?$';
    }

    private function checkPath(string $path): void
    {
        if (!trim($path)) {
            throw new EmptyRoutePathException('The path is empty.');
        }
    }
}
