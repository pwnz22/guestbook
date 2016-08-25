<?php

namespace Core;

class Router
{
    /**
     * @var array
     */
    private $route = [];

    /**
     * @param string $method
     * @param string $path
     * @param callable $callback
     *
     * @return $this
     * @throws Exception
     */
    public function addRoute(string $method, string $path, callable $callback)
    {
        $method = mb_strtoupper($method);
        $path = mb_strtolower($path);
        foreach ($this->route as $route) {
            if ($route['method'] == $method && $route['path'] == $path) {
                throw new Exception('Method: <b>' . $method . '</b> and Path: <b>' . $path . '</b>');
            }
        }
        $this->route[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
//        return $this->route;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function dispatch()
    {
        $output = [];
        foreach ($this->route as $value) {
            if ($this->isMethod($value['method']) && $this->isPath($value['path'])) {
                $output[] = $value['callback'];
            }
        }
        if (empty($output)) {
            return '404';
        }
        return call_user_func($output[0]);
    }

    /**
     * @param string $method
     *
     * @return bool
     */
    private function isMethod(string $method)
    {
        return $method == mb_strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    private function isPath(string $url)
    {
        return $url == $this->getUri();
    }

    /**
     * @return string
     */
    private function getUri()
    {
        return mb_strtolower(rawurldecode(explode('?', $_SERVER['REQUEST_URI'])[0]));
    }
}

