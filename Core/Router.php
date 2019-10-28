<?php

namespace Core;

class Router {
    private $request;
    private $route;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function __call($name, $arguments) {
        list($route, $method) = $arguments;

        $pattern = $this->generateRegex($route);

        $this->{strtolower($name)}[$this->formatRoute($route)] = [
            'method' => $method,
            'params' => $pattern
        ];
    }

    public function resolve() {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formattedRoute = $this->formatRoute($this->request->requestUri);

        if(!isset($methodDictionary[$formattedRoute])) {
            $parsedRoute = $this->resolveRoute($formattedRoute);

            foreach($methodDictionary as $dict) {
                $preparedRoute = $this->generateRegex($dict['params'][0]);

                if($preparedRoute[1] == $parsedRoute[1] && isset($parsedRoute[2]) && isset($preparedRoute[2])) {
                    $method = $dict;
                }
            }
        }
        else $method = $methodDictionary[$formattedRoute];

        if(!is_null($method)) {
            echo call_user_func_array($method['method'], [$this->request]);
        }
    }

    public function __destruct() {
        $this->resolve();
    }

    private function formatRoute($route) {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function generateRegex($route) {
        $matches = [];
        preg_match('/\/(\w+)\/{(\w+)}/', $route, $matches);

        return $matches;
    }

    private function resolveRoute($route) {
        $matches = [];

        preg_match('/\/(\w+)\/(\d+)/', $route, $matches);

        return $matches;
    }
}