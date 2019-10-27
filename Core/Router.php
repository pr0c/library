<?php

namespace Core;

class Router {
    private $request;
    private $supportedHttpMethods = [
        "GET",
        "POST"
    ];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function __call($name, $arguments) {
        list($route, $method) = $arguments;
        if(!in_array(strtoupper($name), $this->supportedHttpMethods))
        {
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    public function __destruct() {
        $this->resolve();
    }

    private function resolve() {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formattedRoute = $this->formatRoute($this->request->requestUri);
        $method = $methodDictionary[$formattedRoute];
        if(is_null($method))
        {
            $this->defaultRequestHandler();
            return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    private function formatRoute($route) {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler() {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler() {
        header("{$this->request->serverProtocol} 404 Not Found");
    }
}