<?php

namespace Ivrok\WPRouter;

class WPRouter
{
    private $routes = [];

    public function addRoute(WPRoute $route)
    {
        $this->routes[] = $route;
    }

    public function init()
    {
        add_action('init', [$this, "__setRewriteRule"]);
        add_filter('query_vars', [$this, '__setVars']);
        add_action('template_redirect', [$this, "__doCallback"]);
    }

    public function __setRewriteRule()
    {
        global $wp_rewrite;

        foreach ($this->routes as $route) {

            $query = 'index.php?';

            $queryParts = [];
            $queryParts[] = 'pagename=' . $this->getPageName($route->path);

            foreach ($this->getDynamicVars($route->path) as $indexVar => $varName) {
                $queryParts[] = sprintf('%s=$matches[%d]', $varName, ($indexVar + 1));
            }

            $path = $route->getPathRegex($route->path);

            add_rewrite_rule(
                $path,
                $query . implode("&", $queryParts),
                'top' );
        }

        $wp_rewrite->flush_rules();
    }

    public function __setVars($query_vars)
    {
        foreach ($this->routes as $route) {
            if (!$route->isItThisPath($GLOBALS['wp']->request)) {
                continue;
            }
            foreach ($this->getDynamicVars($route->path) as $varName) {
                if (!in_array($varName, $query_vars)) {
                    $query_vars[] = $varName;
                }
            }
        }

        return $query_vars;
    }

    public function __doCallback()
    {
        foreach ($this->routes as $route) {

            if (!$route->isItThisPath($GLOBALS['wp']->request)) {
                continue;
            }

            $GLOBALS['wp_query']->is_404 = false;
            header("HTTP/1.1 200 OK");

            $vars = [];
            foreach ($this->getDynamicVars($route->path) as $varName) {
                $vars[] = (int)get_query_var($varName, false);
            }

            $route->call($vars);

            die;
        }
    }

    private function getDynamicVars($path): array
    {
        $pathVars = [];

        if (preg_match_all("/\{([^}]+)\}/", $path, $matchedVars)) {
            $pathVars = $matchedVars[1];
        }

        return $pathVars;
    }

    private function getPageName($path): string
    {
        return current(explode('/', $path));
    }
}
